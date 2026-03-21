<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WowCommandService
{
    /**
     * Send a command to the Emulator via SOAP.
     * Works for AzerothCore, TrinityCore, Mangos etc.
     */
    public function sendSoapCommand(string $command): bool
    {
        // These should ideally be in .env
        $host = env('WOW_SOAP_HOST', '127.0.0.1');
        $port = env('WOW_SOAP_PORT', 7878);
        $user = env('WOW_SOAP_USER', 'admin');
        $pass = env('WOW_SOAP_PASS', 'admin');

        $uri = "urn:TC";
        if (env('WOW_CORE') === 'azerothcore') {
            $uri = "urn:AC";
        } elseif (env('WOW_CORE') === 'mangos') {
            $uri = "urn:MaNGOS";
        }

        try {
            $client = new \SoapClient(null, [
                'location' => "http://{$host}:{$port}/",
                'uri' => $uri,
                'style' => SOAP_RPC,
                'login' => $user,
                'password' => $pass,
                'trace' => 1,
                'exceptions' => 1,
            ]);

            $result = $client->executeCommand(new \SoapParam($command, 'command'));
            
            Log::info("SOAP Command Executed: {$command}");
            return true;

        } catch (\Exception $e) {
            Log::error("SOAP Error ({$command}): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send an item to a character via SOAP mail
     */
    public function sendItemViaSoap(string $characterName, int $itemId, string $subject = 'CMS Delivery', string $text = 'Thank you for your support!'): bool
    {
        // Example TC/AC command: .send items "CharacterName" "Subject" "Body" itemID
        // Note: Spaces in subject/body usually need to be handled specific to the core.
        // For safety, we keep subject and text simple without spaces if possible, or quote them.
        
        $safeSubject = str_replace(' ', '_', $subject);
        $safeText = str_replace(' ', '_', $text);
        
        $command = ".send items \"{$characterName}\" \"{$safeSubject}\" \"{$safeText}\" {$itemId}";
        return $this->sendSoapCommand($command);
    }

    /**
     * Send Gold to a character via SOAP mail
     * Gold is typically in copper (1 gold = 10000)
     */
    public function sendGoldViaSoap(string $characterName, int $copper, string $subject = 'CMS Delivery', string $text = 'Thank you for your support!'): bool
    {
        $safeSubject = str_replace(' ', '_', $subject);
        $safeText = str_replace(' ', '_', $text);
        
        $command = ".send money \"{$characterName}\" \"{$safeSubject}\" \"{$safeText}\" {$copper}";
        return $this->sendSoapCommand($command);
    }
    
    /**
     * Increase character level via SQL directly, if SOAP is unavailable.
     */
    public function increaseLevelSql(string $characterName, int $levels = 1): bool
    {
        try {
            DB::connection('characters')->table('characters')
                ->where('name', $characterName)
                ->increment('level', $levels);
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to give level to {$characterName}: " . $e->getMessage());
            return false;
        }
    }
}
