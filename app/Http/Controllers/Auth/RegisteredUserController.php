<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Notifications\UserPendingApprovalNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $tenants = Tenant::with('domains')->get();
        return view('auth.register', compact('tenants'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'cpf'       => ['required', 'integer', 'unique:' . User::class],
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
            'tenant_id' => ['required', 'string', 'exists:central.tenants,id'],
        ]);

        $user = User::create([
            'cpf'       => $request->cpf,
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'tenant_id' => $request->tenant_id,
            'is_approved' => false,  // User starts as unapproved
        ]);

        event(new Registered($user));

        // Assign field_officer role and notify admins within the selected tenant's database
        $tenant = Tenant::find($request->tenant_id);
        if ($tenant) {
            tenancy()->initialize($tenant);
            try {
                $user->assignRole('field_officer');
            } catch (\Throwable $e) {
                // Role may not exist yet in this tenant DB; skip gracefully
            }

            // Notify admins about new user registration (while tenant DB is active)
            try {
                $admins = User::role(['super-admin', 'location_manager', 'head_logging_services'])->get();
                foreach ($admins as $admin) {
                    $admin->notify(new UserPendingApprovalNotification($user));
                }
            } catch (\Throwable $e) {
                // Notification failure should not block registration
            }

            tenancy()->end();
        }

        // Notify admins about new user registration
        $admins = User::role(['super-admin', 'Location Manager', 'Head_Logging_Services'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new UserPendingApprovalNotification($user));
        }

        // Do not auto-login; user must wait for approval
        return redirect(route('login'))->with('status', 'Registration successful! Please wait for approval by an administrator before logging in.');
    }
}
