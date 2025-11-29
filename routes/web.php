<?php

use App\Livewire\Centers\Index as CentersIndex;
use App\Livewire\Centers\SingleCenter;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Members\Index as MembersIndex;
use App\Livewire\Members\SingleMember;
use App\Livewire\Permissions\Index as PermissionsIndex;
use App\Livewire\Roles\Index as RolesIndex;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use App\Livewire\Users\Index;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', DashboardIndex::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    Route::get('users' , Index::class)->name('users');
    Route::get('centers', CentersIndex::class)->name('centers');
    Route::get('members', MembersIndex::class)->name('members');
    Route::get('center/{center}/members', SingleCenter::class)->name('singlecenter');
    Route::get('members/{member}', SingleMember::class)->name('singlemember');
    Route::get('roles' , RolesIndex::class)->name('roles');
    Route::get('permissions' , PermissionsIndex::class)->name('permissions');


});
