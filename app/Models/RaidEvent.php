<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaidEvent extends Model
{
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_PUBLISHED = 'published';
    const STATUS_FULL = 'full';
    const STATUS_ROSTER_LOCKED = 'roster_locked';
    const STATUS_READY = 'ready';
    const STATUS_RUNNING = 'running';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    // Timing Types
    const TIMING_LIVE = 'live';
    const TIMING_SCHEDULED = 'scheduled';
    const TIMING_FLEXIBLE = 'flexible';

    protected $fillable = [
        'title', 'instance_name', 'difficulty', 'scheduled_at', 'duration_hours',
        'leader_name', 'leader_user_id', 'max_players', 'min_ilvl_requirement',
        'description', 'status', 'created_by', 'price_per_spot', 'booster_user_id',
        'service_category', 'service_type', 'region', 'coordinator_discord',
        'cloth_spots', 'leather_spots', 'mail_spots', 'plate_spots',
        'bosses_total', 'bosses_killed', 'dynamic_slots',
        'collection_type', 'management_cut_percentage', 'payout_distributed',
        'required_tanks', 'required_healers', 'required_dps', 'service_id',
        'is_queue', 'mythic_plus_level', 'dungeon_name', 'cancel_reason',
        // Custom Raid Request fields
        'creation_source', 'requested_by_user_id', 'assigned_leader_id',
        'armor_stack', 'special_conditions', 'preferred_start_time', 'applications_close_at',
        'timing_type', 'flexible_time_note',
    ];

    protected $casts = [
        'scheduled_at'          => 'datetime',
        'preferred_start_time'  => 'datetime',
        'applications_close_at' => 'datetime',
        'price_per_spot'        => 'decimal:2',
        'dynamic_slots'         => 'array',
        'is_queue'              => 'boolean',
    ];

    public function booster()
    {
        return $this->belongsTo(User::class, 'booster_user_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    public function assignedLeader()
    {
        return $this->belongsTo(User::class, 'assigned_leader_id');
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Alias — RaidEvents created via advertiser request use 'requested_by_user_id'
    // For queue events created by advertisers, 'created_by' is the advertiser
    public function advertiser()
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    public function signups()
    {
        return $this->hasMany(RaidSignup::class);
    }

    public function notes()
    {
        return $this->hasMany(RunNote::class);
    }

    public function service()
    {
        return $this->belongsTo(BoostingService::class, 'service_id');
    }

    public function lockRoster()
    {
        if (in_array($this->status, [self::STATUS_APPROVED, self::STATUS_PUBLISHED, 'open_for_applications'])) {
            $this->update(['status' => self::STATUS_ROSTER_LOCKED]);

            // Notify squad and advertisers
            $squad = $this->signups()->where('status', 'accepted')->get();
            foreach ($squad as $signup) {
                if ($signup->user) {
                    $signup->user->notify(new \App\Notifications\MissionLocked($this));
                }
                if ($signup->advertiser_user_id) {
                    $ad = User::find($signup->advertiser_user_id);
                    if ($ad) $ad->notify(new \App\Notifications\MissionLocked($this));
                }
            }
        }
    }

    public function startRun()
    {
        $startableStatuses = [
            self::STATUS_APPROVED,
            self::STATUS_PUBLISHED,
            self::STATUS_ROSTER_LOCKED,
            self::STATUS_READY,
            'scheduled' // Fallback for legacy data
        ];
        
        if (in_array($this->status, $startableStatuses)) {
            $this->update(['status' => self::STATUS_RUNNING]);

            // Notify squad and advertisers
            $squad = $this->signups()->where('status', 'accepted')->get();
            foreach ($squad as $signup) {
                if ($signup->user_id !== auth()->id() && $signup->user) {
                    $signup->user->notify(new \App\Notifications\MissionStarted($this));
                }
                // Notify the advertiser (solicitor)
                if ($signup->advertiser_user_id) {
                    $advertiser = User::find($signup->advertiser_user_id);
                    if ($advertiser) {
                        $advertiser->notify(new \App\Notifications\MissionStarted($this));
                    }
                }
            }
        }
    }

    public function completeRun()
    {
        if ($this->status === self::STATUS_RUNNING && !$this->payout_distributed) {
            $this->update(['status' => self::STATUS_COMPLETED]);
            
            // Notify squad and advertisers
            $squad = $this->signups()->where('status', 'accepted')->get();
            foreach ($squad as $signup) {
                if ($signup->user_id !== auth()->id() && $signup->user) {
                    $signup->user->notify(new \App\Notifications\MissionCompleted($this));
                }
                // Notify the advertiser (solicitor)
                if ($signup->advertiser_user_id) {
                    $advertiser = User::find($signup->advertiser_user_id);
                    if ($advertiser) {
                        $advertiser->notify(new \App\Notifications\MissionCompleted($this));
                    }
                }
            }

            $this->distributePayouts();
        }
    }

    public function cancelRun($reason = '')
    {
        if (!in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_CANCELLED])) {
            $this->update([
                'status' => self::STATUS_CANCELLED,
                'cancel_reason' => $reason
            ]);

            // Notify everyone who was involved
            $signups = $this->signups()->whereIn('status', ['accepted', 'pending', 'waitlist'])->get();
            foreach ($signups as $signup) {
                if ($signup->user) {
                    $signup->user->notify(new \App\Notifications\MissionCancelled($this, $reason));
                }
            }

            $this->signups()->whereIn('status', ['accepted', 'pending', 'waitlist'])->update(['status' => 'cancelled']);
        }
    }

    public function distributePayouts()
    {
        if ($this->payout_distributed) return;

        $clientSignups = $this->signups()->where('is_booster', false)->where('status', 'accepted')->get();
        $boosterSignups = $this->signups()->where('is_booster', true)->where('attendance_status', 'present')->get();

        if ($clientSignups->isEmpty() || $boosterSignups->isEmpty()) {
            $this->update(['payout_distributed' => true]);
            return;
        }

        $totalOrderValue = $clientSignups->sum('agreed_price');
        $managementCutPercent = $this->management_cut_percentage ?: 10;
        $houseGold = ($totalOrderValue * $managementCutPercent) / 100;
        
        // Calculate Advertiser Commissions (Ad Cut)
        $advertiserGold = 0;
        foreach ($clientSignups as $signup) {
            $commission = $signup->ad_cut > 0 ? $signup->ad_cut : ($signup->agreed_price * 0.10);
            $advertiserGold += $commission;

            if ($signup->advertiser_user_id) {
                LedgerTransaction::create([
                    'user_id' => $signup->advertiser_user_id,
                    'amount' => $commission,
                    'type' => 'advertiser_commission',
                    'description' => "Commission for {$signup->character_name} in {$this->title}",
                    'reference_id' => $this->id,
                    'reference_type' => self::class
                ]);
                $signup->advertiser->increment('balance', $commission);
            }
        }

        $boosterGoldPool = $totalOrderValue - $houseGold - $advertiserGold;
        $boosterCount = $boosterSignups->count();
        $perBoosterShare = $boosterGoldPool / $boosterCount;

        foreach ($boosterSignups as $signup) {
            LedgerTransaction::create([
                'user_id' => $signup->user_id,
                'amount' => $perBoosterShare,
                'type' => 'booster_payout',
                'description' => "Payout for participation in {$this->title}",
                'reference_id' => $this->id,
                'reference_type' => self::class
            ]);
            $signup->user->increment('balance', $perBoosterShare);
            $signup->update(['payout_amount' => $perBoosterShare]);
        }

        // Log House Cut (Management)
        $adminUser = User::where('account_type', 'admin')->first();
        if ($adminUser) {
            LedgerTransaction::create([
                'user_id' => $adminUser->id,
                'amount' => $houseGold,
                'type' => 'management_cut',
                'description' => "Management cut from {$this->title}",
                'reference_id' => $this->id,
                'reference_type' => self::class
            ]);
            $adminUser->increment('balance', $houseGold);
        }

        $this->update(['payout_distributed' => true]);
    }

    public function getRoleCounts()
    {
        $signups = $this->signups()->whereIn('status', ['accepted', 'waitlist'])->get();
        
        return [
            'tank' => $signups->where('role', 'tank')->count(),
            'healer' => $signups->where('role', 'healer')->count(),
            'dps' => $signups->where('role', 'dps')->count(),
        ];
    }

    public function getArmorSlotcounts()
    {
        $signups = $this->signups()->whereIn('status', ['accepted', 'waitlist'])->get();
        
        return [
            'cloth' => [
                'taken' => $signups->where('armor_type', 'Cloth')->count(),
                'total' => $this->cloth_spots
            ],
            'leather' => [
                'taken' => $signups->where('armor_type', 'Leather')->count(),
                'total' => $this->leather_spots
            ],
            'mail' => [
                'taken' => $signups->where('armor_type', 'Mail')->count(),
                'total' => $this->mail_spots
            ],
            'plate' => [
                'taken' => $signups->where('armor_type', 'Plate')->count(),
                'total' => $this->plate_spots
            ]
        ];
    }
}
