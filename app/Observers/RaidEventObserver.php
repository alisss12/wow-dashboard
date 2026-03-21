<?php

namespace App\Observers;

use App\Models\RaidEvent;

class RaidEventObserver
{
    /**
     * Handle the RaidEvent "created" event.
     */
    public function created(RaidEvent $raidEvent): void
    {
        //
    }

    /**
     * Handle the RaidEvent "updated" event.
     */
    public function updated(RaidEvent $raidEvent): void
    {
        // When a run is officially marked 'completed', process the professional gold split
        if ($raidEvent->isDirty('status') && $raidEvent->status === 'completed' && !$raidEvent->payout_distributed) {
            
            // Only process if there is an assigned booster
            if (!$raidEvent->booster_user_id) {
                return;
            }

            $booster = $raidEvent->booster;
            $managementUser = \App\Models\User::where('account_type', 'admin')->first(); // Management Cut Repo
            
            $totalGoldVolume = 0;
            $totalAdvertiserCommission = 0;

            // Get all accepted bookings
            $successfulBookings = \App\Models\RaidSignup::where('raid_event_id', $raidEvent->id)
                ->whereIn('status', ['accepted', 'waitlist'])
                ->get();

            foreach ($successfulBookings as $booking) {
                $price = $booking->agreed_price ?? $raidEvent->price_per_spot;
                
                // Mission staff (boosters) don't contribute to volume that pays commissions
                if ($booking->is_booster) {
                    continue; 
                }

                $totalGoldVolume += $price;

                $advertiser = $booking->advertiser;
                if ($advertiser) {
                    // Advertiser Commission Protocol: Global 10%
                    $adPercentage = $booking->advertiser_commission_percentage ?? 10;
                    $adCut = $price * ($adPercentage / 100);
                    $totalAdvertiserCommission += $adCut;

                    $advertiser->increment('balance', $adCut);

                    \App\Models\LedgerTransaction::create([
                        'user_id' => $advertiser->id,
                        'amount' => $adCut,
                        'type' => 'commission',
                        'description' => "Commission: client {$booking->character_name} in Protocol Mission: {$raidEvent->title}",
                        'reference_id' => $raidEvent->id,
                        'reference_type' => RaidEvent::class
                    ]);
                }
            }

            // House/Management Cut Protocol: Global 10%
            $mgmtPercentage = $raidEvent->management_cut_percentage ?? 10;
            $managementGold = $totalGoldVolume * ($mgmtPercentage / 100);

            // Mission Lead Cut (Roster/Booster Pool): Remaining 80%
            $boosterCut = $totalGoldVolume - $managementGold - $totalAdvertiserCommission;

            // 1. Credit House Administration
            if ($managementUser && $managementGold > 0) {
                $managementUser->increment('balance', $managementGold);
                \App\Models\LedgerTransaction::create([
                    'user_id' => $managementUser->id,
                    'amount' => $managementGold,
                    'type' => 'management_fee',
                    'description' => "Protocol House Cut: {$raidEvent->title} (Volume: {$totalGoldVolume})",
                    'reference_id' => $raidEvent->id,
                    'reference_type' => RaidEvent::class
                ]);
            }

            // 2. Credit Mission Staff (All participating boosters)
            if ($boosterCut > 0) {
                $participatingBoosters = $successfulBookings->where('is_booster', true);
                $boosterCount = $participatingBoosters->count();

                if ($boosterCount > 0) {
                    $individualCut = $boosterCut / $boosterCount;
                    
                    foreach ($participatingBoosters as $boosterRecord) {
                        $boosterUser = $boosterRecord->user;
                        if ($boosterUser) {
                            $boosterUser->increment('balance', $individualCut);
                            
                            \App\Models\LedgerTransaction::create([
                                'user_id' => $boosterUser->id,
                                'amount' => $individualCut,
                                'type' => 'run_payout',
                                'description' => "Mission Staff Payout: {$raidEvent->title} (Share: 1/{$boosterCount})",
                                'reference_id' => $raidEvent->id,
                                'reference_type' => RaidEvent::class
                            ]);
                        }
                    }
                } else {
                    // Fallback to the Raid Leader if no specific signups found
                    $booster->increment('balance', $boosterCut);
                    \App\Models\LedgerTransaction::create([
                        'user_id' => $booster->id,
                        'amount' => $boosterCut,
                        'type' => 'run_payout',
                        'description' => "Mission Lead Payout (Fallback): {$raidEvent->title}",
                        'reference_id' => $raidEvent->id,
                        'reference_type' => RaidEvent::class
                    ]);
                }
            }

            // Initialize Distribution Lock
            $raidEvent->payout_distributed = true;
            $raidEvent->saveQuietly();
        }
    }

    /**
     * Handle the RaidEvent "deleted" event.
     */
    public function deleted(RaidEvent $raidEvent): void
    {
        //
    }

    /**
     * Handle the RaidEvent "restored" event.
     */
    public function restored(RaidEvent $raidEvent): void
    {
        //
    }

    /**
     * Handle the RaidEvent "force deleted" event.
     */
    public function forceDeleted(RaidEvent $raidEvent): void
    {
        //
    }
}
