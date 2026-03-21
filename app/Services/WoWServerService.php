<?php

namespace App\Services;

class WoWServerService
{
    /**
     * Checks if a user's password matches the game server auth database.
     * NEED EMULATOR CORE (AzerothCore/TrinityCore/Mangos) to define SRP6/SHA1 hashing.
     */
    public function verifyRemotePassword($username, $plainPassword, $remoteHash)
    {
        throw new \Exception("WoW Server hashing logic not implemented. Please specify your Emulator Core.");
    }

    /**
     * Delivers an item to the character via SOAP or direct DB insert.
     * NEED EMULATOR CORE to define RaGEZONE/SOAP API payload.
     */
    public function deliverItem($characterName, $itemId, $amount = 1)
    {
        throw new \Exception("WoW Server item delivery logic not implemented. Please specify your Emulator Core.");
    }
    
    /**
     * Delivers an instant level up to the character via SOAP or direct DB insert.
     */
    public function deliverLevel($characterName, $level)
    {
        throw new \Exception("WoW Server level delivery logic not implemented. Please specify your Emulator Core.");
    }
}
