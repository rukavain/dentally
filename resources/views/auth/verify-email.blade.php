<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
</head>

<x-guest-layout>
    <div x-data="{ showModal: false, termsAccepted: false }" class="mt-4">
        <div class="mb-4 text-sm text-gray-600">
            {{ __('Thanks for signing up! Before getting started, you need to verify your email address and accept our Terms and Conditions.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('A new verification link has been sent to your email address.') }}
            </div>
        @endif

        <!-- Terms and Conditions Acceptance -->
        <div class="flex items-start mb-4">
            <input type="checkbox" name="accept_terms" id="accept_terms" class="mt-1" x-model="termsAccepted"
                required>
            <label for="accept_terms" class="ml-2 text-sm">
                {{ __('I have read and agree to the ') }}
                <button type="button" @click="showModal = true" class="text-blue-600 hover:underline">
                    {{ __('Terms and Conditions') }}
                </button>
            </label>
        </div>

        <!-- Modal -->
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <!-- Modal Backdrop -->
            <div class="fixed inset-0 bg-black opacity-50"></div>

            <!-- Modal Content -->
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">{{ __('Terms and Conditions') }}</h3>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="prose max-w-none">
                        <h4 class="font-bold">1. Acceptance of Terms</h4>
                        <p class="mb-4">By accessing and using this dental system, you agree to be bound by these
                            Terms and Conditions.</p>

                        <h4 class="font-bold">2. Privacy and Data Protection</h4>
                        <p class="mb-4">We collect and process personal information in accordance with our Privacy
                            Policy. By using our services, you consent to such processing.</p>

                        <h4 class="font-bold">3. User Responsibilities</h4>
                        <p class="mb-4">Users must maintain the confidentiality of their account credentials and are
                            responsible for all activities under their account.</p>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button @click="showModal = false"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            {{ __('Close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <div>
                    <x-primary-button x-bind:disabled="!termsAccepted"
                        x-bind:class="{ 'opacity-50 cursor-not-allowed': !termsAccepted }" class="bg-green-600">
                        {{ __('Send Verification Email') }}
                    </x-primary-button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="bg-gray-600 text-xs text-white py-2 px-4 font-semibold hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('LOG OUT') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
