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
                            Background</h1>
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
            <h1 class="text-3xl font-bold mb-6">Personal Information</h1>
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

                        @if ($patient->has_hmo)
                            <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="next_visit">
                                <h1>HMO Company</h1>
                                <select id="hmo_company" name="hmo_company" onchange="toggleOtherHmoField()"
                                    class="border border-gray-400 py-2 px-4 rounded-md max-md:text-xs max-md:py-1 max-md:px-2">
                                    <option value="">Select HMO</option>
                                    <option value="HMO1"
                                        {{ old('hmo_company', $patient->hmo_company) == 'HMO1' ? 'selected' : '' }}>
                                        HMO1
                                    </option>
                                    <option value="HMO2"
                                        {{ old('hmo_company', $patient->hmo_company) == 'HMO2' ? 'selected' : '' }}>
                                        HMO2
                                    </option>
                                    <option value="other"
                                        {{ old('hmo_company', $patient->hmo_company) == 'other' ? 'selected' : '' }}>
                                        Other: {{ $patient->hmo_company }}
                                    </option> {{-- <option value="HMO2">
                                        {{ old('hmo_company', $patient->hmo_company) == 'HMO2' ? 'selected' : '' }}>
                                        HMO2
                                    </option>
                                    <option value="other">
                                        {{ old('hmo_company', $patient->hmo_company) == 'other' ? 'selected' : '' }}>
                                        Other
                                    </option> --}}
                                </select>
                                @error('hmo_company')
                                    <span id="next_visit_error"
                                        class="validation-message text-red-600 text-xs p-1 rounded-md show">{{ $message }}</span>
                                @enderror
                            </label>
                        @else
                        @endif


                        <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="phone_number">
                            <h1>Phone number</h1>
                            <input class="border border-gray-400 py-2 px-4 rounded-md" name="phone_number" type="text"
                                autocomplete="off" oninput="validateInput('phone_number')"
                                value="{{ old('phone_number', $patient->phone_number) }}" id="phone_number">
                            @error('phone_number')
                                <span id="phone_number_error"
                                    class="validation-message text-red-600 text-xs p-1 rounded-md show">{{ $message }}</span>
                            @enderror
                        </label>


                        <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="phone_number">
                            <h1>Phone number</h1>
                            <input class="border border-gray-400 py-2 px-4 rounded-md" name="phone_number" type="text"
                                autocomplete="off" oninput="validateInput('phone_number')"
                                value="{{ old('phone_number', $patient->phone_number) }}" id="phone_number">
                            @error('phone_number')
                                <span id="phone_number_error"
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

    {{-- <section class="bg-white max-lg:mt-14 m-4 p-8 shadow-lg rounded-md flex flex-wrap justify-between z-0">
        <div class="flex  w-full max-lg:flex-col flex-wrap justify-between items-start">
            <div class="flex w-full flex-wrap justify-between items-start">
                <div class="flex flex-wrap justify-between mb-6 gap-4 items-start ">
                    <div class="flex flex-col">
                        <div>
                            <h1 class="text-5xl mb-4 font-bold max-md:text-3xl">
                            </h1>
                            <div class="flex flex-col justify-start items-start gap-3 text-md mb-5">
                                <h1 class=" max-md:text-sm"> Gender: <span class="font-semibold">
                                        {{ $patient->patient_gender }}
                                    </span>
                                </h1>
                                <h1 class=" max-md:text-sm"> Birth date: <span class="font-semibold">
                                        {{ $patient->patient_birth_date }}
                                    </span>
                                </h1>
                                <h1 class=" max-md:text-sm"> patient specialization: <span class="font-semibold">
                                        {{ $patient->patient_specialization }}
                                    </span>
                                </h1>
                                <h1 class=" max-md:text-sm"> Branch: <span class="font-semibold">
                                        @if ($patient->branch_id === 1)
                                            Dau
                                        @elseif($patient->branch_id === 2)
                                            Angeles
                                        @elseif($patient->branch_id === 3)
                                            Sindalan
                                        @endif
                                    </span> </h1>
                                <h1 class=" max-md:text-sm"> Phone number: <span class="font-semibold">
                                        {{ $patient->patient_phone_number }}
                                    </span> </h1>
                            </div>
                            <a class="flex justify-center gap-3 items-center border border-slate-600 rounded-md py-2 px-4 max-md:py-1 max-md:px-2 text-white font-semibold hover:bg-gray-400 transition-all w-max"
                                href=" {{ route('edit.patient', $patient->id) }} ">
                                <img class="h-7 sm:h-4 sm:w-4 max-sm:h-4 max-sm:w-4"
                                    src="{{ asset('assets/images/edit-icon.png') }}" alt="">
                                <h1 class="text-slate-900 max-md:hidden">Edit information</h1>
                            </a>
                        </div>
                    </div>


                </div>
            </div>
    </section> --}}
@endsection
