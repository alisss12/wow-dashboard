<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VoteSite;
use App\Models\VoteLog;
use Carbon\Carbon;

class VoteController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $sites = VoteSite::where('active', true)->get();
        
        $sites->transform(function ($site) use ($user) {
            $lastVote = VoteLog::where('user_id', $user->id)
                ->where('vote_site_id', $site->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $site->can_vote = true;
            $site->next_vote_at = null;

            if ($lastVote) {
                $nextVoteTime = $lastVote->created_at->addHours($site->cooldown_hours);
                if (now()->lessThan($nextVoteTime)) {
                    $site->can_vote = false;
                    $site->next_vote_at = $nextVoteTime;
                }
            }
            return $site;
        });

        return view('vote.index', compact('sites'));
    }

    public function vote(Request $request, VoteSite $voteSite)
    {
        $user = auth()->user();

        // Check if active
        if (!$voteSite->active) {
            return redirect()->back()->with('error', 'This voting site is currently inactive.');
        }

        // Check cooldown
        $lastVote = VoteLog::where('user_id', $user->id)
            ->where('vote_site_id', $voteSite->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastVote) {
            $nextVoteTime = $lastVote->created_at->addHours($voteSite->cooldown_hours);
            if (now()->lessThan($nextVoteTime)) {
                return redirect()->back()->with('error', 'You must wait before voting on this site again.');
            }
        }

        // Log the vote and apply points directly (in a real system of top-lists, a pingback/callback from the site is usually required, but this assumes immediate rewards on click for simplicity)
        VoteLog::create([
            'user_id' => $user->id,
            'vote_site_id' => $voteSite->id,
            'ip_address' => $request->ip(),
            'claimed_at' => now(),
            'reward_given' => true,
        ]);

        $user->increment('vote_points', $voteSite->reward_points);
        $user->update(['last_vote_at' => now()]);

        return redirect()->away($voteSite->url);
    }
}
