<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {
        return view('profile.profile', [
            'user' => $request->user(),
        ]);
    }

    public function update_avatar(Request $request)
    {
        // Handle the user upload of avatar
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg',
        ]);
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $user = Auth::user();
            $filename = 'IMG-' . $user->cpf . '.' . $avatar->getClientOriginalExtension();
            
            // Delete old avatar from central public disk if exists
            if ($user->avatar) {
                Storage::disk('public')->delete('images/profile_image/' . $user->avatar);
            }

            $avatar->storeAs('images/profile_image', $filename, 'public');
            $user->avatar = $filename;
            $user->save();
        }

        return Redirect::route('profile.edit')->with('status', 'avatar-updated');

    }

    public function edit(Request $request): View
    {
        $tenants = Tenant::with('domains')->get();
        return view('profile.edit', [
            'user' => $request->user(),
            'tenants' => $tenants,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $previousTenantId = $user->tenant_id;

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $newTenantId = $request->validated()['tenant_id'] ?? null;
        $tenant = $newTenantId ? Tenant::find($newTenantId) : null;

        if ($newTenantId && $newTenantId !== $previousTenantId) {
            // When location changes, require admin approval for the new tenant.
            $user->is_approved = false;
            $user->approved_at = null;
            $user->approved_by = null;
        }

        $user->save();

        if ($newTenantId && $newTenantId !== $previousTenantId) {
            if ($tenant) {
                tenancy()->initialize($tenant);
                try {
                    if (!$user->hasRole('field_officer')) {
                        $user->assignRole('field_officer');
                    }
                } catch (\Throwable $e) {
                    // Silently ignore if roles table not available
                }
                tenancy()->end();
            }

            $admins = \App\Models\User::role(['super-admin', 'head_logging_services', 'Head_Logging_Services'])->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\UserLocationChangeApprovalNotification($user, $tenant, $previousTenantId));
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::route('login')->with('status', 'location-changed');
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
