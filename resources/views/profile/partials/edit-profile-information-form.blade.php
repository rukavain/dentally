<section>
    <header>
        <h2 class="text-lg font-medium text-white">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-white">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="username" class="text-white" :value="__('Username')" />
            <x-text-input id="username" name="username" type="text"
                class=" w-full border border-gray-400 py-2 px-4 rounded-md max-md:text-sm" :value="old('username', $user->username)" required
                autofocus autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="email" class="text-white" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email"
                class=" w-full border border-gray-400 py-2 px-4 rounded-md max-md:text-sm" :value="old('email', $user->email)" required
                autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
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

        <div class="flex justify-end items-center">
            <button
                class="py-2 px-8 font-semibold rounded-md text-white bg-slate-600 hover:bg-green-600 hover:text-white  transition-all
                        max-md:flex max-md:justify-center max-md:items-center max-md:py-1 max-md:text-center max-md:px-2 max-md:text-sm"
                type="submit">
                Update
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 mx-5">{{ __('Updated.') }}</p>
            @endif
        </div>
    </form>
</section>
