<x-guest-layout>
    @if ($errors->any())
        <div class="mb-4 text-red-500">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.customer') }}">
        @csrf

        {{-- name --}}
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- email --}}
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- phone --}}
        <div class="mt-3">
            <x-input-label for="phone" value="Phone Number" />
            <x-text-input id="phone" class="block mt-1 w-full" name="phone" type="text" required />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        {{-- address --}}
        <div class="mt-3">
            <x-input-label for="address" value="Address" />
            <x-text-input id="address" name="address" class="mt-1 block w-full" required />
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-5">
            <a href="{{ route('register.vendor') }}" class="underline text-gray-500 text-sm hover:text-gray-700">
                Register as Vendor →
            </a>

            <button class="ms-3 bg-indigo-600 text-white py-2 px-6 rounded">
                Register
            </button>
        </div>
    </form>
</x-guest-layout>
