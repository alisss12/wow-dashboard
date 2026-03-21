<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CharacterLookupController extends Controller
{
    public function lookup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'realm' => 'required|string',
            'region' => 'nullable|string'
        ]);

        $name = $request->input('name');
        $realm = $request->input('realm');
        $region = $request->input('region', 'eu');

        // raider.io API expects slugs for realms
        $realmSlug = str_replace([' ', "'"], ['-', ''], strtolower($realm));

        try {
            $response = Http::withoutVerifying()
                ->timeout(10)
                ->get("https://raider.io/api/v1/characters/profile", [
                    'region' => $region,
                    'realm' => $realmSlug,
                    'name' => $name,
                    'fields' => 'gear'
                ]);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Character not found on Raider.io'
                ], 404);
            }

            $data = $response->json();
            $class = $data['class'] ?? null;
            $spec = $data['active_spec_name'] ?? null;
            $apiRole = $data['active_spec_role'] ?? 'dps';
            
            $armorType = $this->getArmorType($class);
            $internalRole = $this->getInternalRole($class, $spec, $apiRole);

            return response()->json([
                'success' => true,
                'class' => $class,
                'spec' => $spec,
                'role' => $internalRole,
                'name' => $data['name'],
                'realm' => $data['realm'],
                'thumbnail' => $data['thumbnail_url'],
                'armor_type' => $armorType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'API timeout or connection error'
            ], 500);
        }
    }

    private function getInternalRole($class, $spec, $apiRole)
    {
        if (strtolower($apiRole) === 'tank') return 'tank';
        if (strtolower($apiRole) === 'healer') return 'healer';

        // MDPS Specs
        $meleeMapping = [
            'Warrior' => ['Arms', 'Fury'],
            'Paladin' => ['Retribution'],
            'Death Knight' => ['Frost', 'Unholy'],
            'Hunter' => ['Survival'],
            'Shaman' => ['Enhancement'],
            'Rogue' => ['Assassination', 'Outlaw', 'Subtlety'],
            'Druid' => ['Feral'],
            'Demon Hunter' => ['Havoc'],
            'Monk' => ['Windwalker'],
        ];

        // Check against the melee mapping
        if (isset($meleeMapping[$class]) && in_array($spec, $meleeMapping[$class])) {
            return 'mdps';
        }

        // RDPS Specs (Everything else that isn't Tank/Healer/Melee)
        // Includes: Mage, Warlock, Priest (Shadow), Shaman (Elemental), Hunter (Beast Mastery/Marksmanship), Druid (Balance), Evoker (Devastation/Augmentation)
        return 'rdps';
    }

    private function getArmorType($class)
    {
        $map = [
            'Warrior' => 'Plate',
            'Paladin' => 'Plate',
            'Death Knight' => 'Plate',
            'Hunter' => 'Mail',
            'Shaman' => 'Mail',
            'Evoker' => 'Mail',
            'Rogue' => 'Leather',
            'Druid' => 'Leather',
            'Monk' => 'Leather',
            'Demon Hunter' => 'Leather',
            'Mage' => 'Cloth',
            'Warlock' => 'Cloth',
            'Priest' => 'Cloth',
        ];

        return $map[$class] ?? 'N/A';
    }
}
