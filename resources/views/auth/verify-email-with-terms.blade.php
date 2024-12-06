<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Thanks for signing up! Before getting started, you need to:') }}
        <ul class="list-disc ml-5 mt-2">
            <li>{{ __('Verify your email address') }}</li>
            <li>{{ __('Accept our Terms and Conditions') }}</li>
        </ul>
    </div>
    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification link has been sent to your email address.') }}
        </div>
    @endif
    <div class="mt-4 bg-white p-4 rounded-lg shadow">
        <h3 class="font-semibold mb-2">{{ __('Terms and Conditions') }}</h3>
        <div class="h-48 overflow-y-auto mb-4 p-4 border rounded">
            <!-- Add your terms and conditions text here -->
            <h4 class="font-bold">1. Acceptance of Terms</h4>
            <p class="mb-2">By accessing and using this dental system, you agree to be bound by these Terms and
                Conditions.</p>

            <h4 class="font-bold">2. Privacy and Data Protection</h4>
            <p class="mb-2">We collect and process personal information in accordance with our Privacy Policy. By using
                our services, you consent to such processing.</p>

            <h4 class="font-bold">3. User Responsibilities</h4>
            <p class="mb-2">Users must maintain the confidentiality of their account credentials and are responsible
                for all activities under their account.</p>

            <!-- Add more sections as needed -->
        </div>
        <form method="POST" action="{{ route('verification.verify-with-terms') }}" class="space-y-4">
            @csrf

            <div class="flex items-start">
                <input type="checkbox" name="accept_terms" id="accept_terms" class="mt-1" required>
                <label for="accept_terms" class="ml-2 text-sm">
                    {{ __('I have read and agree to the Terms and Conditions') }}
                </label>
            </div>
            <div>
                <x-primary-button>
                    {{ __('Accept Terms & Verify Email') }}
                </x-primary-button>
            </div>
        </form>
        <form method="POST" action="{{ route('verification.send') }}" class="mt-4">
            @csrf
            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>
    </div>
    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit"
            class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            {{ __('Log Out') }}
        </button>
    </form>
</x-guest-layout>
