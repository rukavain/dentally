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
                <h1 class="text-xl font-bold">
                    {{ $staff->first_name }}
                    {{ $staff->last_name }}</h1>
                <h1 class="text-sm">
                    {{ $staff->user->role === 'staff' ? 'Staff' : '' }}
                </h1>
                <h1>
                    @if ($staff->user->email_verified_at !== null)
                        <p class="text-green-600 text-xs font-semibold">
                            &#10004; Email verified</p>
                    @else
                        <p class="text-red-600 text-xs font-semibold"> &#10005; Email not verified.</p>
                    @endif
                </h1>
            </div>
            <div class="mt-8 flex flex-col justify-start items-start gap-4">
                <a href=""
                    class="flex  w-full justify-start items-center gap-4 hover:bg-gray-200 transition-all py-2 px-4 rounded-md border">
                    <img class="h-6 border rounded-full" src="{{ asset('assets/images/user-icon.png') }}" alt="">
                    <h1 class="text-sm font-semibold">Personal Information</h1>
                </a>
            </div>
        </section>
        <section class="max-lg:mt-12">
            <h1 class="text-3xl font-bold mb-6">Personal Information</h1>
            <form method="POST" action="{{ route('update.staff', $staff->id) }}">
                @method('PUT')
                @csrf
                <div class="flex flex-col items-center">
                    <div
                        class="flex flex-wrap items-start justify-start gap-8 max-md:gap-4 max-w-4xl p-8 max-md:p-2 flex-row">
                        <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="first_name">
                            <h1>First name</h1>
                            <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-sm" name="first_name"
                                type="text" id="first_name" value="{{ old('firstname', $staff->first_name) }}"
                                autocomplete="off" placeholder="Juan" oninput="validateInput('first_name')">
                            @error('first_name')
                                <span id="first_name_error"
                                    class="validation-message text-red-600 text-xs p-1 rounded-md  show">{{ $message }}</span>
                            @enderror
                        </label>
                        <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="last_name">
                            <h1>Last name</h1>
                            <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-sm" name="last_name"
                                type="text" id="last_name" value="{{ old('last_name', $staff->last_name) }}"
                                autocomplete="off" placeholder="Dela Cruz" oninput="validateInput('last_name')">
                            @error('last_name')
                                <span id="last_name_error"
                                    class="validation-message text-red-600 text-xs p-1 rounded-md  show">{{ $message }}</span>
                            @enderror
                        </label>
                        <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="email">
                            <h1>Email</h1>
                            <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-sm" name="email"
                                type="text" autocomplete="off" oninput="validateInput('email')" id="email"
                                value="{{ $staff->email }}" readonly>
                            @error('email')
                                <span id="email_error"
                                    class="validation-message text-red-600 text-xs p-1 rounded-md  show">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="flex flex-col flex-1 min-w-[45%] max-md:text-sm" for="phone_number">
                            <h1>Phone number</h1>
                            <input class="border border-gray-400 py-2 px-4 rounded-md max-md:text-sm" name="phone_number"
                                type="text" autocomplete="off" oninput="validateInput('phone_number')"
                                value="{{ old('phone_number', $staff->phone_number) }}" id="phone_number">
                            @error('phone_number')
                                <span id="phone_number_error"
                                    class="validation-message text-red-600 text-xs p-1 rounded-md  show">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="flex flex-col flex-1 min-w-[45%] pb-4" for="branch_id">
                            <h1 class="max-md:text-sm">Branch</h1>
                            <select
                                class="border flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md max-md:text-sm max-md:py-1 max-md:px-2"
                                id="branch_id" name="branch_id" required>
                                <option value="1" {{ old('branch_id', $staff->branch_id) === 1 ? 'selected' : '' }}>
                                    Dau
                                </option>
                                <option value="2" {{ old('branch_id', $staff->branch_id) === 2 ? 'selected' : '' }}>
                                    Angeles
                                </option>
                                <option value="3" {{ old('branch_id', $staff->branch_id) === 3 ? 'selected' : '' }}>
                                    Sindalan</option>
                            </select>
                            @error('branch_id')
                                <span id="branch_id_error"
                                    class="validation-message text-white max-md:text-sm bg-red-600 p-1 rounded-md my-1 show">{{ $message }}</span>
                            @enderror
                        </label>
                        <label class="flex flex-col flex-1 min-w-[45%]" for="fb_name">
                            <h1 class="max-md:text-sm">Facebook Name</h1>
                            <input
                                class="border border-gray-400 py-2 px-4 rounded-md max-md:text-sm max-md:py-1 max-md:px-2"
                                name="fb_name" type="text" id="fb_name" autocomplete="off"
                                value="{{ old('fb_name', $staff->fb_name) }}" oninput="validateInput('fb_name')">
                            @error('fb_name')
                                <span id="email_error"
                                    class="validation-message text-white max-md:text-sm bg-red-600 p-1 rounded-md my-1 show">{{ $message }}</span>
                            @enderror
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
                            href=" {{ route('staff') }} ">
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
                                        {{ $staff->gender }}
                                    </span>
                                </h1>
                                <h1 class=" max-md:text-sm"> Birth date: <span class="font-semibold">
                                        {{ $staff->birth_date }}
                                    </span>
                                </h1>
                                <h1 class=" max-md:text-sm"> staff specialization: <span class="font-semibold">
                                        {{ $staff->specialization }}
                                    </span>
                                </h1>
                                <h1 class=" max-md:text-sm"> Branch: <span class="font-semibold">
                                        @if ($staff->branch_id === 1)
                                            Dau
                                        @elseif($staff->branch_id === 2)
                                            Angeles
                                        @elseif($staff->branch_id === 3)
                                            Sindalan
                                        @endif
                                    </span> </h1>
                                <h1 class=" max-md:text-sm"> Phone number: <span class="font-semibold">
                                        {{ $staff->phone_number }}
                                    </span> </h1>
                            </div>
                            <a class="flex justify-center gap-3 items-center border border-slate-600 rounded-md py-2 px-4 max-md:py-1 max-md:px-2 text-white font-semibold hover:bg-gray-400 transition-all w-max"
                                href=" {{ route('edit.staff', $staff->id) }} ">
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
