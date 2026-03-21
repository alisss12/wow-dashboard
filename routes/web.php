<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RaidController;
use App\Http\Controllers\RaidManagementController;
use App\Http\Controllers\RaidSignupController;
use App\Http\Controllers\MythicPlusController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\BoosterApplicationController;
use App\Http\Controllers\RaidRequestController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\CharacterLookupController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/discord', [\App\Http\Controllers\Auth\DiscordController::class, 'redirectToDiscord'])->name('discord.login');
Route::get('/auth/discord/callback', [\App\Http\Controllers\Auth\DiscordController::class, 'handleDiscordCallback'])->name('discord.callback');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // --- Core Systems ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/live-refresh', [DashboardController::class, 'apiRefresh'])->name('dashboard.refresh');
    Route::get('/api/character-lookup', [CharacterLookupController::class, 'lookup'])->name('api.character.lookup');

    // Logout fallback
    Route::get('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout.get');

    // Profile Management
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Public Information / Documentation
    Route::prefix('info')->group(function () {
        Route::get('/guide', fn() => view('advertiser-guide'))->name('guide.advertiser');
        Route::get('/guide/booster', fn() => view('guide-booster'))->name('guide.booster');
        Route::get('/tos', fn() => view('legal.tos'))->name('legal.tos');
    });

    // --- Shared Features (Raids & M+) ---
    Route::resource('raids', RaidController::class)->only(['index', 'show']);
    Route::post('/raids/{raid}/signup', [RaidController::class, 'signup'])->name('signups.store');
    Route::post('/raids/{raid}/take-spot', [RaidController::class, 'takeSpot'])->name('raids.takeSpot');

    Route::resource('signups', RaidSignupController::class)->only(['edit', 'update', 'destroy']);
    Route::post('/signups/{signup}/status', [RaidSignupController::class, 'updateStatus'])->name('signups.updateStatus');
    Route::post('/signups/{signup}/confirm-payment', [RaidSignupController::class, 'confirmPayment'])->name('signups.confirmPayment');

    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::post('/shop/purchase/{shopItem}', [ShopController::class, 'purchase'])->name('shop.purchase');
    
    Route::get('/vote', [VoteController::class, 'index'])->name('vote.index');
    Route::post('/vote/{voteSite}', [VoteController::class, 'vote'])->name('vote.submit');

    // --- Notifications ---
    Route::post('/notifications/{id}/read', function ($id) {
        auth()->user()->notifications()->where('id', $id)->update(['read_at' => now()]);
        return back();
    })->name('notifications.markRead');
    
    Route::post('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markAllRead');

    /*
    |--------------------------------------------------------------------------
    | Booster Specific Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:booster'])->group(function () {
        // Raid Management (Booster as Leader)
        Route::prefix('management')->name('raids.manage.')->group(function () {
            Route::get('/create', [RaidManagementController::class, 'create'])->name('create');
            Route::post('/', [RaidManagementController::class, 'store'])->name('store');
            Route::get('/{raid}/edit', [RaidManagementController::class, 'edit'])->name('edit');
            Route::put('/{raid}', [RaidManagementController::class, 'update'])->name('update');
        });

        // Operational Commands
        Route::controller(RaidManagementController::class)->prefix('raids/{raid}')->name('raid-management.')->group(function () {
            Route::post('/lock', 'lockRoster')->name('lock');
            Route::post('/start', 'start')->name('start');
            Route::post('/complete', 'complete')->name('complete');
            Route::post('/report', 'reportIssue')->name('report');
            Route::post('/cancel', 'cancel')->name('cancel');
            Route::post('/duplicate', 'duplicate')->name('duplicate');
            Route::post('/add-booster', 'addBooster')->name('add-booster');
            Route::post('/toggle-attendance/{signup}', 'toggleAttendance')->name('toggle-attendance');
            Route::post('/approve-booster/{signup}', 'approveBooster')->name('approve-booster');
            Route::post('/reject-booster/{signup}', 'rejectBooster')->name('reject-booster');
            Route::delete('/remove-booster/{signup}', 'removeBooster')->name('remove-booster');
        });

        // Mythic+ Fulfillment
        Route::post('/mythic-plus/{event}/join', [MythicPlusController::class, 'joinGroup'])->name('mythic-plus.join');

        // Payouts
        Route::post('/payout/request', [FinancialController::class, 'requestPayout'])->name('financial.payout');
    });

    /*
    |--------------------------------------------------------------------------
    | Advertiser Specific Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:advertiser'])->group(function () {
        // Recruitment
        Route::post('/booster/apply', [BoosterApplicationController::class, 'store'])->name('booster.apply');
        
        // Mythic+ Requests
        Route::post('/mythic-plus/request', [MythicPlusController::class, 'storeRequest'])->name('mythic-plus.store');

        // Custom Raid Requests
        Route::resource('raid-requests', RaidRequestController::class)->only(['store', 'index']);
        Route::get('/my-raid-requests', [RaidRequestController::class, 'myRequests'])->name('raid-requests.my');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin / Leader Specific Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/raid-requests/{raidRequest}/apply', [RaidRequestController::class, 'apply'])->name('raid-requests.apply');
        Route::post('/raid-requests/{raidRequest}/publish', [RaidRequestController::class, 'publish'])->name('raid-requests.publish');
        Route::post('/raid-requests/{raidRequest}/assign/{signup}', [RaidRequestController::class, 'assignLeader'])->name('raid-requests.assign');
    });
});

require __DIR__.'/auth.php';
