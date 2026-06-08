<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>
    <div class="row g-3">
        <div class="col-md-6 order-2">
            <form method="post" action="{{ route('profile.update') }}" class="mt-6">
                @csrf
                @method('patch')

                <div class="mb-2">
                    <x-input-label for="name" :value="__('Name')">Name</x-input-label>
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                        value="{{ old('name') ? old('name') : $user->name}}" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div class="mb-2">
                    <x-input-label for="designation" :value="__('Designation')">Designation</x-input-label>
                    <x-text-input id="designation" name="designation" type="text" class="mt-1 block w-full"
                        value="{{ old('designation') ? old('designation') : $user->designation}}" required autofocus
                        autocomplete="designation" />
                    <x-input-error class="mt-2" :messages="$errors->get('designation')" />
                </div>
                <div class="mb-2">
                    <x-input-label for="phone" :value="__('Phone')">Phone</x-input-label>
                    <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full"
                        value="{{ old('phone') ? old('phone') : $user->phone}}" required autofocus
                        autocomplete="phone" />
                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                </div>


                <div class="mb-2">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                        <div class="mb-2">
                            <p class="text-sm mt-2 text-gray-800">
                                {{ __('Your email address is unverified.') }}

                                <button form="send-verification"
                                    class="btn btn-light underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Click here to send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="mb-2">
                    <x-input-label for="description" :value="__('Description')">Description</x-input-label>
                    <textarea id="description" name="description" type="text" class="form-control mt-1 block w-full"
                        required autofocus
                        autocomplete="description">{{ old('description') ? old('description') : $user->description}}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>

                <!-- Location (Tenant) -->
                <div class="mb-2">
                    <x-input-label for="tenant_id" :value="__('Location')">Location</x-input-label>
                    <select id="tenant_id" name="tenant_id" class="form-control mt-1 block w-full">
                        <option value="">-- Select Location --</option>
                        @foreach($tenants ?? [] as $t)
                            @if($t->domains->isNotEmpty())
                                <option value="{{ $t->id }}" {{ (old('tenant_id', $user->tenant_id) === $t->id) ? 'selected' : '' }}>
                                    {{ ucfirst($t->id) }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('tenant_id')" />
                </div>

                <div class="flex items-center gap-4 mt-1">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>

                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                    @endif
                </div>
            </form>
        </div>

        <div class="col-md-6 order-1">
            <div class="d-flex justify-content-center">
                <image id="avatar" name="avatar" class="mt-1 block w-full rounded-circle"
                    src="{{ asset('storage/images/profile_image/' . $user->avatar) }}" required autofocus style="height: 15rem; width: 15rem; object-fit: cover;"
                    autocomplete="avatar" />
            </div>
            <form method="post" action="{{ route('profile.update_avatar') }}" class="mt-6" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="m-2">
                    <x-text-input id="avatar" name="avatar" type="file" class="mt-1 block w-full"
                        value="{{ old('avatar') ? old('avatar') : $user->avatar}}" autofocus
                        autocomplete="avatar"></x-text-input>
                    <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                </div>
                <div class="flex items-center gap-4 mt-1">
                    <x-primary-button>{{ __('Upload') }}</x-primary-button>

                    @if (session('status') === 'avatar-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>