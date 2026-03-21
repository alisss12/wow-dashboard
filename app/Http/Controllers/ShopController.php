<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopCategory;
use App\Models\ShopItem;

class ShopController extends Controller
{
    public function index()
    {
        $categories = ShopCategory::orderBy('order')->get();
        
        // Group items by category to make it easier for the blade template
        $itemsByCategory = [];
        foreach ($categories as $category) {
            $itemsByCategory[$category->id] = ShopItem::where('category_id', $category->id)
                ->where('active', true)
                ->get();
        }

        return view('shop.index', compact('categories', 'itemsByCategory'));
    }

    public function purchase(Request $request, ShopItem $shopItem, \App\Services\WowCommandService $wowCommandService)
    {
        $user = auth()->user();
        
        $request->validate([
            'character_name' => 'required|string|max:255',
            'currency' => 'required|in:vote,donate'
        ]);

        $characterName = $request->input('character_name');
        $currency = $request->input('currency');
        $price = $currency === 'vote' ? $shopItem->price_vote : $shopItem->price_donate;

        if (!$price || $price <= 0) {
            return redirect()->back()->with('error', 'This item cannot be purchased with that currency.');
        }

        $balanceField = $currency === 'vote' ? 'vote_points' : 'donation_points';
        
        if ($user->{$balanceField} < $price) {
            return redirect()->back()->with('error', 'You do not have enough points.');
        }

        try {
            $data = json_decode($shopItem->data, true) ?: [];

            // Attempt delivery first before deducting points
            $delivered = false;
            if ($shopItem->type === 'item') {
                $delivered = $wowCommandService->sendItemViaSoap($characterName, $data['item_id'] ?? 0, 'Shop Purchase', "Enjoy your {$shopItem->name}!");
            } elseif ($shopItem->type === 'level') {
                $delivered = $wowCommandService->increaseLevelSql($characterName, $data['level'] ?? 1); // Give X levels
            } elseif ($shopItem->type === 'gold') {
                $copper = ($data['gold'] ?? 1) * 10000;
                $delivered = $wowCommandService->sendGoldViaSoap($characterName, $copper, 'Shop Purchase', "Enjoy your {$shopItem->name}!");
            }

            if (!$delivered) {
                return redirect()->back()->with('error', 'The Game Server failed to process the delivery. Please try again later.');
            }

            // Deduct Points
            $user->decrement($balanceField, $price);

            return redirect()->back()->with('success', 'Purchase successful! Log into the game to receive your rewards.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An internal error occurred: ' . $e->getMessage());
        }
    }
}
