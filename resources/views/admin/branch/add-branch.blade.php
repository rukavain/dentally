@extends('admin.dashboard')
@section('content')
    <style>
        /* Fade-in and Fade-out CSS */
        .validation-message {
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .validation-message.show {
            display: block;
            opacity: 1;
        }

        .validation-message.hide {
            opacity: 0;
        }
    </style>
    <div class="m-4">
        @include('components.search')
    </div>
    <section class="bg-white shadow-lg rounded-md max-w-max p-6 my-4 mx-auto  max-lg:mt-14">
        <h1 class="font-bold text-3xl px-4 max-md:text-2xl">Add Branch</h1>
        <form method="POST" action="{{ route('branch.store') }}">
            @method('POST')
            @csrf
            <div class="flex flex-col items-start justify-start gap-8 max-w-4xl p-4">
                <div class="w-full ">

                    <label class="flex flex-col flex-1 min-w-[45%] pb-3" for="branch_loc">
                        <h1 class="max-md:text-sm">Branch Location</h1>
                        <input
                            class="border flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md max-md:text-sm max-md:py-1 max-md:px-2"
                            name="branch_loc" type="text" id="branch_loc" autocomplete="off" placeholder="Dau"
                            value="{{ old('branch_loc') }}" oninput="validateInput('branch_loc')">
                        @error('branch_loc')
                            <span id="branch_loc_error"
                                class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                </div>
                <div class="flex gap-4 ">
                    <button
                        class="py-2 px-8 max-md:text-xs max-md:py-2 max-md:px-4 font-semibold rounded-md hover:bg-green-600 hover:border-green-600 hover:text-white text-gray-800 border-2 border-gray-600 transition-all"
                        type="submit">
                        Add
                    </button>
                    <button
                        class="py-2 max-md:text-xs max-md:py-2 max-md:px-4 px-8 font-semibold rounded-md hover:bg-gray-600 border-2 border-gray-600 hover:text-white text-gray-800  transition-all"
                        type="reset">
                        Reset
                    </button>
                    <a href=" {{ route('branch') }} "
                        class="py-2 max-md:text-xs max-md:py-2 max-md:px-4 flex justify-center items-center px-8 font-semibold rounded-md hover:bg-red-600 hover:border-red-600 border-2 border-gray-600 text-gray-800  hover:text-white transition-all"
                        type="reset">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </section>
@endsection
