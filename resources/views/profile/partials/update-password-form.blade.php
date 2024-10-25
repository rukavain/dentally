<section>
    <header>
        <h2 class="text-lg font-medium text-white">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-white">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" class="text-white" :value="__('Current Password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password"
                class=" w-full border border-gray-400 py-2 px-4 rounded-md max-md:text-sm"
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>
        {{-- akdkashdahsdjhs --}}
        <div>
            <x-input-label for="update_password_password" class="text-white" :value="__('New Password')" />
            <x-text-input id="update_password_password" name="password" type="password"
                class=" w-full border border-gray-400 py-2 px-4 rounded-md max-md:text-sm"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" class="text-white" :value="__('Confirm Password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class=" w-full border border-gray-400 py-2 px-4 rounded-md max-md:text-sm"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>


        <div class="flex justify-end items-center">
            <button
                class="py-2 px-8 font-semibold rounded-md text-white bg-slate-600 hover:bg-green-600 hover:text-white  transition-all
                        max-md:flex max-md:justify-center max-md:items-center max-md:py-1 max-md:text-center max-md:px-2 max-md:text-sm"
                type="submit">
                Update
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 mx-5">{{ __('Updated.') }}</p>
            @endif
        </div>
    </form>
</section>
