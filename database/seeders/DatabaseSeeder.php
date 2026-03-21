<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a dummy user
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('admin123'),
                'vote_points' => 50,
                'donation_points' => 100,
            ]
        );

        // 2. Create Dummy Vote Sites
        \App\Models\VoteSite::firstOrCreate(['name' => 'Top100Arena'], [
            'url' => 'https://top100arena.com/vote/xxxx',
            'reward_points' => 2,
            'cooldown_hours' => 12,
            'active' => true,
        ]);
        \App\Models\VoteSite::firstOrCreate(['name' => 'Xtremetop100'], [
            'url' => 'https://xtremetop100.com/vote/xxxx',
            'reward_points' => 1,
            'cooldown_hours' => 24,
            'active' => true,
        ]);

        // 3. Create Shop Categories
        $mountCategory = \App\Models\ShopCategory::firstOrCreate(['slug' => 'mounts'], [
            'name' => 'Epic Mounts',
            'order' => 1,
        ]);
        $gearCategory = \App\Models\ShopCategory::firstOrCreate(['slug' => 'gear'], [
            'name' => 'PvP Gear',
            'order' => 2,
        ]);

        // 4. Create Shop Items
        \App\Models\ShopItem::firstOrCreate(['slug' => 'invincible'], [
            'name' => 'Invincible\'s Reins',
            'category_id' => $mountCategory->id,
            'price_vote' => 100,
            'price_donate' => 50,
            'type' => 'item',
            'data' => json_encode(['item_id' => 50818]),
            'description' => 'A legendary skeletal steed.',
        ]);
        
        \App\Models\ShopItem::firstOrCreate(['slug' => 'shadowmourne'], [
            'name' => 'Shadowmourne',
            'category_id' => $gearCategory->id,
            'price_vote' => null,
            'price_donate' => 150,
            'type' => 'item',
            'data' => json_encode(['item_id' => 49623]),
            'description' => 'A great two-handed axe.',
        ]);

        \App\Models\ShopItem::firstOrCreate(['slug' => 'level-80'], [
            'name' => 'Instant Level 80 Boost',
            'category_id' => $gearCategory->id,
            'price_vote' => 200,
            'price_donate' => 10,
            'type' => 'level',
            'data' => json_encode(['level' => 80]),
            'description' => 'Skip the grind and reach max level instantly.',
        ]);
        
        // 5. News Announcements
        \App\Models\News::firstOrCreate(['slug' => 'server-launch'], [
            'title' => 'Welcome to the Server!',
            'content' => 'We are excited to announce the grand opening of our new World of Warcraft realm.',
            'author_id' => $user->id,
            'published_at' => now(),
        ]);
    }
}
