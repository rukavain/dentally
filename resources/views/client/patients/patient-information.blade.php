@extends('admin.dashboard')
@section('content')
    <div class="m-4">
        @include('components.search')
    </div>

    <section
        class="flex flex-wrap max-lg:mt-12 justify-around items-start bg-white p-4 py-12 m-4 rounded-md border shadow-lg">
        <section class=" h-full">
            <div class="flex flex-col justify-center items-center gap-3">
                <img class="h-40 rounded-full p-2 bg-white border " src="{{ asset('assets/images/user-icon.png') }}"
                    alt="">
                <h1 class="text-xl font-bold">{{ $patient->first_name }}
                    {{ $patient->last_name }}</h1>
                <h1 class="text-sm">
                    {{ $patient->user->role === 'client' ? 'Patient' : '' }}
                </h1>
                <h1>
                    @if ($patient->user->email_verified_at !== null)
                        <p class="text-green-600 text-xs font-semibold">
                            &#10004; Email verified</p>
                    @else
                        <p class="text-red-600 text-xs font-semibold"> &#10005; Email not verified.</p>
                    @endif
                </h1>
            </div>
            <div class="mt-8 flex flex-col justify-start items-start gap-4">
                <div class="flex flex-col gap-4">
                    <a href="{{ route('patient.contract', $patient->id) }}"
                        class="flex text-sm items-center justify-start gap-2 py-1 px-4 font-semibold border border-gray-500 rounded-md hover:border-gray-700 hover:shadow-sm transition-all">
                        <img class="h-4" src="{{ asset('assets/images/contract.png') }}" alt="">
                        <h1>
                            Contract</h1>
                    </a>
                    <a href="{{ route('patient.background', $patient->id) }}"
                        class=" flex text-sm items-center justify-start gap-2 py-1 px-4 font-semibold border border-gray-500 rounded-md hover:border-gray-700 hover:shadow-sm transition-all">
                        <img class="h-4" src="{{ asset('assets/images/background.png') }}" alt="">
                        <h1>
                            Dental Record</h1>
                    </a>
                    <a href="{{ route('patient.xray', $patient->id) }}"
                        class=" flex text-sm items-center justify-start gap-2 py-1 px-4 font-semibold border border-gray-500 rounded-md hover:border-gray-700 hover:shadow-sm transition-all">
                        <img class="h-4" src="{{ asset('assets/images/x-ray.png') }}" alt="">
                        <h1>
                            X-rays</h1>
                    </a>
                    <a href="{{ route('payments.list', $patient->id) }}"
                        class=" flex text-sm items-center justify-start gap-2 py-1 px-4 font-semibold border border-gray-500 rounded-md hover:border-gray-700 hover:shadow-sm transition-all">
                        <img class="h-4" src="{{ asset('assets/images/payment.png') }}" alt="">
                        <h1>
                            Payment</h1>
                    </a>

                    <a
                        class=" flex text-sm items-center justify-start gap-2 py-1 px-4 font-semibold border border-gray-500 rounded-md hover:border-gray-700 hover:shadow-sm transition-all">
                        @if (is_null($patient->archived_at))
                            <img class="h-4" src="{{ asset('assets/images/archive.png') }}" alt="">
                            <button type="submit"
                                onclick="document.getElementById('my_modal_6').showModal()">Archive</button>
                            <dialog id="my_modal_6"
                                class="modal_1 border-2 shadow-lg border-gray-400 p-8 rounded-md max-md:text-lg">
                                <div class="modal-box flex flex-col">
                                    <h3 class="text-2xl font-bold max-md:text-sm">Archive Patient</h3>
                                    <p class="py-4 font-normal max-md:text-sm">Are you sure you want to archive
                                        {{ $patient->last_name . ' ' . $patient->first_name }}?</p>
                                    <div class="modal-action flex gap-2 self-end">
                                        <form method="dialog" class="border rounded-md w-max py-2 px-4">
                                            <button class="btn max-md:text-xs">Close</button>
                                        </form>
                                        <form action="{{ route('archive.patient', $patient->id) }}" method="POST"
                                            class="border  bg-red-600 text-white rounded-md py-2 px-4">
                                            @csrf
                                            <button
                                                class="btn  bg-red-600 text-white max-md:text-xs w-max flex gap-2">Yes</button>
                                        </form>
                                    </div>
                                </div>
                            </dialog>
                        @else
                            <img class="h-4" src="{{ asset('assets/images/restore.png') }}" alt="">
                            <form action="{{ route('restore.patient', $patient->id) }}" method="POST">
                                @csrf
                                <button type="submit">Restore</button>
                            </form>
                        @endif
                    </a>
                </div>
            </div>
        </section>
        <section class="max-lg:mt-12">
            <div class="flex items-center gap-4 mb-6">
                <h1 class="text-3xl font-bold ml-4">Personal Information</h1>
                <button onclick="document.getElementById('hmoModal').classList.remove('hidden')"
                    class="flex text-sm items-center justify-start gap-2 py-1 px-4 font-semibold border border-gray-500 rounded-md hover:border-gray-700 hover:shadow-sm transition-all">
                    <h1>HMO</h1>
                </button>
            </div>
            <form method="POST" action="{{ route('update.patient', $patient->id) }}">
                @method('PUT')
                @csrf
                <div class="flex flex-col items-center">
                    <div
                        class="flex flex-wrap items-start justify-start gap-8 max-md:gap-4 max-w-4xl p-8 max-md:p-2 flex-row">
                        <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="first_name">
                            <h1>First name</h1>
                            <input class="border border-gray-400 py-2 px-4 rounded-md" name="first_name" type="text"
                                id="first_name" value="{{ old('firstname', $patient->first_name) }}" autocomplete="off"
                                placeholder="Juan" oninput="validateInput('first_name')">
                            @error('first_name')
                                <span id="first_name_error"
                                    class="validation-message text-red-600 text-xs p-1 rounded-md show">{{ $message }}</span>
                            @enderror
                        </label>
                        <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="last_name">
                            <h1>Last name</h1>
                            <input class="border border-gray-400 py-2 px-4 rounded-md" name="last_name" type="text"
                                id="last_name" value="{{ old('last_name', $patient->last_name) }}" autocomplete="off"
                                placeholder="Dela Cruz" oninput="validateInput('last_name')">
                            @error('last_name')
                                <span id="last_name_error"
                                    class="validation-message text-red-600 text-xs p-1 rounded-md show">{{ $message }}</span>
                            @enderror
                        </label>
                        <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="date_of_birth">
                            <h1>Date of birth</h1>
                            <input class="border border-gray-400 py-2 px-4 rounded-md" name="date_of_birth" type="date"
                                value="{{ old('date_of_birth', $patient->date_of_birth) }}" id="date_of_birth"
                                oninput="validateInput('date_of_birth')">
                            @error('date_of_birth')
                                <span id="date_of_birth_error"
                                    class="validation-message text-red-600 text-xs p-1 rounded-md show">{{ $message }}</span>
                            @enderror
                        </label>
                        <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="patient_email">
                            <h1>Email</h1>
                            <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-sm" name="patient_email"
                                type="text" autocomplete="off" oninput="validateInput('patient_email')"
                                id="patient_email" value="{{ $patient->email }}" readonly>
                            @error('patient_email')
                                <span id="patient_email_error"
                                    class="validation-message text-red-600 text-xs p-1 rounded-md  show">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="gender">
                            <h1>Gender</h1>
                            <select class="border border-gray-400 py-2 px-4 rounded-md" name="gender" id="gender"
                                oninput="validateInput('gender')">
                                <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>
                                    Male
                                </option>
                                <option value="female"
                                    {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>
                                    Female</option>
                                <option value="others"
                                    {{ old('gender', $patient->gender) == 'others' ? 'selected' : '' }}>
                                    Others</option>
                                <option value="prefer-not-to-say"
                                    {{ old('gender', $patient->gender) == 'prefer-not-to-say' ? 'selected' : '' }}>Prefer
                                    not to
                                    say</option>
                            </select>
                            @error('gender')
                                <span id="gender_error"
                                    class="validation-message text-red-600 text-xs p-1 rounded-md show">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="next_visit">
                            <h1>Date of next visit</h1>
                            <input class="border border-gray-400 py-2 px-4 rounded-md" name="next_visit" type="date"
                                value="{{ old('next_visit', $patient->next_visit) }}" autocomplete="off" id="next_visit"
                                oninput="validateInput('next_visit')">
                            @error('next_visit')
                                <span id="next_visit_error"
                                    class="validation-message text-red-600 text-xs p-1 rounded-md show">{{ $message }}</span>
                            @enderror
                        </label>



                        <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="fb_name">
                            <h1>Facebook name</h1>
                            <input class="border border-gray-400 py-2 px-4 rounded-md" name="fb_name" type="text"
                                autocomplete="off" id="fb_name" value="{{ old('fb_name', $patient->fb_name) }}"
                                placeholder="Dela Cruz" oninput="validateInput('fb_name')">
                            @error('fb_name')
                                <span id="fb_name_error"
                                    class="validation-message text-red-600 text-xs p-1 rounded-md show">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="flex flex-col flex-1 min-w-[45%] " for="branch_id">
                            <h1 class="">Select Branch</h1>
                            <select class="border border-gray-400 py-2 px-4 rounded-md  max-md:py-1 max-md:px-2"
                                id="branch_id" name="branch_id" required>
                                <option class="max-md:text-xs" value="">Select your branch</option>
                                @foreach ($branches as $branch)
                                    @if (isset($patient->branch_id))
                                        {{ $patient->branch_id }}
                                    @else
                                        No branch yet.
                                    @endif
                                    <option class="max-md:text-xs" value="{{ $branch->id }}"
                                        {{ $branch->id == $patient->branch_id ? 'selected' : '' }}>
                                        {{ $branch->branch_loc }}
                                    </option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                    <div class="w-full flex gap-2 px-8 mb-3 mt-8">
                        <button
                            class="flex-1 justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-green-600 hover:border-green-600 hover:text-white text-gray-800 border-2 border-gray-600 transition-all"
                            type="submit">
                            Update
                        </button>
                        <button
                            class="flex-1 justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-gray-600 border-2 border-gray-600 hover:text-white text-gray-800  transition-all"
                            type="reset">
                            Reset
                        </button>
                        <a class="flex-1 justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-red-600 hover:border-red-600 border-2 border-gray-600 text-gray-800  hover:text-white transition-all"
                            href=" {{ route('patient.active') }} ">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </section>
    </section>

    <!-- HMO Modal -->
    <div id="hmoModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-4/5 max-w-4xl shadow-lg rounded-md bg-white">
            <div class="flex flex-col gap-4">
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b pb-4">
                    <h3 class="text-xl font-semibold text-gray-900">HMO Information</h3>
                    <button onclick="document.getElementById('hmoModal').classList.add('hidden')"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <form method="POST" action="{{ route('update.patient.hmo', $patient->id) }}" class="space-y-4">
                    @method('PUT')
                    @csrf
                    <div class="w-full">
                        <label for="hmo">Do you have an HMO?</label>
                        <input type="hidden" name="has_hmo" value="{{ $patient->has_hmo ? '1' : '0' }}">
                        <input type="checkbox" id="hmo" name="has_hmo" value="1"
                            {{ $patient->has_hmo ? 'checked' : '' }} onclick="toggleHmoFields()">
                    </div>

                    <div id="hmoFields" class="flex flex-col flex-1"
                        style="display: {{ $patient->has_hmo ? 'block' : 'none' }};">
                        <div class="flex gap-4 max-md:gap-2">
                            <div class="flex flex-col flex-1">
                                <label for="hmo_company">
                                    <h1 class="max-md:text-sm">Select HMO Company</h1>
                                </label>
                                <select id="patient_hmo_company" name="hmo_company" onchange="toggleOtherHmoField()"
                                    class="border border-gray-400 py-2 px-4 rounded-md max-md:text-xs max-md:py-1 max-md:px-2">
                                    <option value="">Select HMO</option>
                                    <option value="Maxicare" {{ $patient->hmo_company == 'Maxicare' ? 'selected' : '' }}>
                                        Maxicare</option>
                                    <option value="PhilHealth"
                                        {{ $patient->hmo_company == 'PhilHealth' ? 'selected' : '' }}>PhilHealth</option>
                                    <option value="Medicard" {{ $patient->hmo_company == 'Medicard' ? 'selected' : '' }}>
                                        Medicard</option>
                                    <option value="Intellicare"
                                        {{ $patient->hmo_company == 'Intellicare' ? 'selected' : '' }}>Intellicare</option>
                                    <option value="other"
                                        {{ !in_array($patient->hmo_company, ['', 'Maxicare', 'PhilHealth', 'Medicard', 'Intellicare']) ? 'selected' : '' }}>
                                        Other</option>
                                </select>
                            </div>
                            <div id="patient_otherHmo" class="flex flex-col flex-1"
                                style="display: {{ !in_array($patient->hmo_company, ['', 'Maxicare', 'PhilHealth', 'Medicard', 'Intellicare']) ? 'block' : 'none' }};">
                                <label for="other_hmo_name">
                                    <h1 class="max-md:text-sm">Enter HMO Company Name:</h1>
                                </label>
                                <input type="text" id="other_hmo_name" name="other_hmo_name"
                                    value="{{ !in_array($patient->hmo_company, ['', 'Maxicare', 'PhilHealth', 'Medicard', 'Intellicare']) ? $patient->hmo_company : '' }}"
                                    class="w-full max-md:text-sm max-md:py-1 max-md:px-2 border border-gray-400 py-2 px-4 rounded-md">
                            </div>
                        </div>
                        <div class="flex gap-4 max-md:gap-2">
                            <label for="hmo_number" class="flex flex-col flex-1">
                                <h1 class="max-md:text-sm">HMO Number:</h1>
                                <input type="text" id="hmo_number" name="hmo_number"
                                    value="{{ $patient->hmo_number }}"
                                    class="w-full max-md:text-sm max-md:py-1 max-md:px-2 border border-gray-400 py-2 px-4 rounded-md">
                            </label>
                            <label for="hmo_type" class="flex flex-col flex-1">
                                <h1 class="max-md:text-sm">Type of HMO:</h1>
                                <input type="text" id="hmo_type" name="hmo_type" value="{{ $patient->hmo_type }}"
                                    class="w-full max-md:text-sm max-md:py-1 max-md:px-2 border border-gray-400 py-2 px-4 rounded-md">
                            </label>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" onclick="document.getElementById('hmoModal').classList.add('hidden')"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleHmoFields() {
            const hmoCheckbox = document.getElementById('hmo');
            const hmoFields = document.getElementById('hmoFields');
            const hasHmoInput = document.querySelector('input[type="hidden"][name="has_hmo"]');

            hmoFields.style.display = hmoCheckbox.checked ? 'block' : 'none';
            hasHmoInput.value = hmoCheckbox.checked ? '1' : '0';

            if (!hmoCheckbox.checked) {
                document.getElementById('patient_hmo_company').value = '';
                document.getElementById('other_hmo_name').value = '';
                document.getElementById('hmo_number').value = '';
                document.getElementById('hmo_type').value = '';
                toggleOtherHmoField();
            }
        }

        function toggleOtherHmoField() {
            const hmoCompanySelect = document.getElementById('patient_hmo_company');
            const otherHmo = document.getElementById('patient_otherHmo');
            const otherHmoInput = document.getElementById('other_hmo_name');

            otherHmo.style.display = hmoCompanySelect.value === 'other' ? 'block' : 'none';

            if (hmoCompanySelect.value !== 'other') {
                otherHmoInput.value = '';
            }
        }

        // Initialize the form state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const hmoCheckbox = document.getElementById('hmo');
            toggleHmoFields();
            toggleOtherHmoField();
        });
    </script>
@endsection
