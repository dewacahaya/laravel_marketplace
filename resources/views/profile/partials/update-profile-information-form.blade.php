<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Account Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Update your account name, email, and contact and address information.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Data USERS (Name) --}}
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Data USERS (Email) --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address has not been verified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <h3 class="text-md font-medium text-gray-900 dark:text-gray-100 mt-8 pt-4 border-t border-gray-200">
            {{ __('Contact Information') }}
        </h3>

        @if ($user->role === 'customer')
            {{-- CUSTOMER FIELDS --}}
            @php $profile = $user->customerProfile; @endphp

            {{-- Phone --}}
            <div>
                <x-input-label for="phone" :value="__('Nomor Telepon')" />
                <x-text-input
                    id="phone"
                    name="phone"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old('phone', $profile->phone ?? '')"
                    required
                    autocomplete="tel"
                />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            {{-- Address --}}
            <div>
                <x-input-label for="address" :value="__('Alamat Pengiriman Default')" />
                <textarea
                    id="address"
                    name="address"
                    rows="3"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    required
                >{{ old('address', $profile->address ?? '') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>

        @elseif ($user->role === 'vendor')
            {{-- VENDOR FIELDS --}}
            @php $profile = $user->vendorProfile; @endphp

            {{-- Store Name --}}
            <div>
                <x-input-label for="store_name" :value="__('Nama Toko')" />
                <x-text-input
                    id="store_name"
                    name="store_name"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old('store_name', $profile->store_name ?? '')"
                    required
                />
                <x-input-error class="mt-2" :messages="$errors->get('store_name')" />
            </div>

            {{-- Address --}}
            <div>
                <x-input-label for="address" :value="__('Alamat Toko (Lokasi Pengiriman)')" />
                <textarea
                    id="address"
                    name="address"
                    rows="3"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    required
                >{{ old('address', $profile->address ?? '') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>
        @endif


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Update') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
