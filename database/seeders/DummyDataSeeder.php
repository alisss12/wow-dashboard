<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Dummy Users
        $booster = \App\Models\User::updateOrCreate(
            ['email' => 'booster@wow.com'],
            [
                'name' => 'BOOSTER_TEST',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'account_type' => 'booster',
                'balance' => 0,
            ]
        );

        $advertiser = \App\Models\User::updateOrCreate(
            ['email' => 'advertiser@wow.com'],
            [
                'name' => 'ADVERTISER_TEST',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'account_type' => 'advertiser',
                'balance' => 50000, // Rich advertiser
            ]
        );

        // 2. Create Dummy Raids for the Booster
        $pendingRaid = \App\Models\RaidEvent::create([
            'title' => 'ICC 25H - Pending Review',
            'instance_name' => 'Icecrown Citadel',
            'difficulty' => '25 Heroic',
            'scheduled_at' => now()->addDays(2),
            'duration_hours' => 3,
            'leader_name' => 'BoosterTest',
            'leader_user_id' => $booster->id,
            'max_players' => 25,
            'min_ilvl_requirement' => 264,
            'status' => 'pending',
            'price_per_spot' => 50.00,
            'booster_user_id' => $booster->id,
        ]);

        $approvedRaid = \App\Models\RaidEvent::create([
            'title' => 'Vault of Archavon - Approved',
            'instance_name' => 'Vault of Archavon',
            'difficulty' => '10 Normal',
            'scheduled_at' => now()->addDays(1),
            'duration_hours' => 1,
            'leader_name' => 'BoosterTest',
            'leader_user_id' => $booster->id,
            'max_players' => 10,
            'min_ilvl_requirement' => 200,
            'status' => 'approved',
            'price_per_spot' => 15.00,
            'booster_user_id' => $booster->id,
        ]);

        // 3. Create a Dummy Booking from the Advertiser
        \App\Models\RaidSignup::create([
            'raid_event_id' => $approvedRaid->id,
            'user_id' => $advertiser->id,
            'advertiser_user_id' => $advertiser->id,
            'character_guid' => 12345,
            'character_name' => 'ClientMage',
            'buyer_realm' => 'Icecrown',
            'buyer_faction' => 'alliance',
            'role' => 'DPS',
            'class' => 'Mage',
            'spec' => 'Fire',
            'status' => 'waitlist',
            'agreed_price' => 15.00,
            'notes' => 'Booked via dummy seeder',
        ]);

        $this->command->info('Dummy users and runs generated! Try logging in with booster@wow.com / advertiser@wow.com');
    }
}
