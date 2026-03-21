<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BoostingService;
use App\Models\RaidEvent;
use App\Models\RaidSignup;
use App\Models\LedgerTransaction;
use Illuminate\Support\Facades\Hash;

class ProcessAuditSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create specific test users
        $admin = User::updateOrCreate(['email' => 'commander@hq.com'], [
            'name' => 'Commander HQ',
            'password' => Hash::make('password'),
            'account_type' => 'admin',
            'balance' => 1000000,
        ]);

        $advertiser = User::updateOrCreate(['email' => 'market@maker.com'], [
            'name' => 'Market Maker',
            'password' => Hash::make('password'),
            'account_type' => 'advertiser',
            'balance' => 50000,
        ]);

        $booster = User::updateOrCreate(['email' => 'swift@blade.com'], [
            'name' => 'Swift Blade',
            'password' => Hash::make('password'),
            'account_type' => 'booster',
            'balance' => 25000,
        ]);

        // 2. Seed Services
        $mplusService = BoostingService::updateOrCreate(['name' => '+15 Timing Protocol'], [
            'category' => 'Mythic+',
            'base_price' => 150000,
            'required_boosters' => 4,
            'max_clients' => 1,
            'is_active' => true,
        ]);

        $heroicRaid = BoostingService::updateOrCreate(['name' => 'Heroic Nerub-ar Palace Full Clear'], [
            'category' => 'Raid',
            'base_price' => 500000,
            'required_boosters' => 18,
            'max_clients' => 12,
            'is_active' => true,
        ]);

        // 3. Create a Mythic+ Order in "Scouting" (Booster has joined)
        $mplusScouting = RaidEvent::create([
            'title' => 'M+15 Timing',
            'instance_name' => 'The Stonevault',
            'difficulty' => 'Mythic',
            'region' => 'EU',
            'service_category' => 'Mythic+',
            'service_type' => '+15 Timing Protocol',
            'service_id' => $mplusService->id,
            'price_per_spot' => 150000,
            'max_players' => 5,
            'status' => 'scouting',
            'is_queue' => true,
            'mythic_plus_level' => 15,
            'scheduled_at' => now()->addMinutes(15),
            'timing_type' => 'live',
            'created_by' => $advertiser->id,
            'requested_by_user_id' => $advertiser->id,
            'leader_user_id' => $booster->id,
            'leader_name' => $booster->name,
        ]);

        // Advertiser Signup for M+
        RaidSignup::create([
            'raid_event_id' => $mplusScouting->id,
            'user_id' => $advertiser->id,
            'character_name' => 'RichBuyer-Kazzak',
            'buyer_realm' => 'Kazzak',
            'buyer_faction' => 'horde',
            'agreed_price' => 150000,
            'advertiser_user_id' => $advertiser->id,
            'status' => 'accepted',
            'is_booster' => false,
            'role' => 'client',
            'class' => 'Warrior',
            'attendance_status' => 'present',
        ]);

        // Booster Signup for M+
        RaidSignup::create([
            'raid_event_id' => $mplusScouting->id,
            'user_id' => $booster->id,
            'character_name' => 'SwiftBlade-Draenor',
            'role' => 'tank',
            'class' => 'Paladin',
            'status' => 'accepted',
            'is_booster' => true,
            'attendance_status' => 'present',
        ]);

        // 4. Create a Mythic+ Order in "Queued" (Waiting)
        RaidEvent::create([
            'title' => 'M+10 Key Push',
            'instance_name' => 'Ara-Kara',
            'difficulty' => 'Mythic',
            'region' => 'EU',
            'service_category' => 'Mythic+',
            'service_type' => '+10 Key Push',
            'price_per_spot' => 80000,
            'max_players' => 5,
            'status' => 'queued',
            'is_queue' => true,
            'mythic_plus_level' => 10,
            'scheduled_at' => now()->addMinutes(30),
            'timing_type' => 'live',
            'created_by' => $advertiser->id,
            'requested_by_user_id' => $advertiser->id,
        ]);

        // 5. Create a "Running" Raid Event
        $runningRaid = RaidEvent::create([
            'title' => 'Heroic Full Run',
            'instance_name' => 'Nerub-ar Palace',
            'difficulty' => 'Heroic',
            'region' => 'NA',
            'service_category' => 'Raid',
            'service_type' => 'Full Clear',
            'service_id' => $heroicRaid->id,
            'price_per_spot' => 450000,
            'max_players' => 30,
            'status' => 'running',
            'is_queue' => false,
            'scheduled_at' => now()->subHour(),
            'duration_hours' => 3,
            'booster_user_id' => $booster->id,
            'leader_user_id' => $booster->id,
            'leader_name' => $booster->name,
            'created_by' => $booster->id,
        ]);

        // 6. Create a "Completed" Raid with Payouts distributed
        $completedRaid = RaidEvent::create([
            'title' => 'Vault Express',
            'instance_name' => 'Nerub-ar Palace',
            'difficulty' => 'Heroic',
            'region' => 'EU',
            'service_category' => 'Raid',
            'service_type' => '4/8 Heroic',
            'price_per_spot' => 200000,
            'max_players' => 20,
            'status' => 'completed',
            'is_queue' => false,
            'scheduled_at' => now()->subDays(2),
            'payout_distributed' => true,
            'booster_user_id' => $booster->id,
            'leader_user_id' => $booster->id,
            'leader_name' => $booster->name,
            'created_by' => $booster->id,
        ]);

        // Add dummy transactions for the completed raid
        LedgerTransaction::create([
            'user_id' => $booster->id,
            'amount' => 150000,
            'type' => 'booster_payout',
            'description' => "Payout for Vault Express",
            'reference_id' => $completedRaid->id,
            'reference_type' => RaidEvent::class,
        ]);
    }
}
