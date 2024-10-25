@extends('admin.dashboard')
@section('content')
    <style>
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

        .loading-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            /* Ensure it appears above other content */
        }

        .spinner {
            border: 8px solid #f3f3f3;
            /* Light grey */
            border-top: 8px solid #3498db;
            /* Blue */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <div class="m-4 mb-8">
        @include('components.search')
    </div>
    <section class="bg-white shadow-lg rounded-md max-w-max p-6 my-4 mx-auto  max-lg:mt-14">
        <div class="p-4">
            <h1 class="font-bold text-3xl pb-2">Edit Branch</h1>
        </div>
        <form action="{{ route('branch.update', $branch->id) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="flex flex-col items-start justify-start gap-8 max-w-4xl p-4">
                <div class="w-full ">

                    <label class="flex flex-col flex-1 min-w-[45%] pb-3" for="branch">
                        <h1 class="max-md:text-sm">Branch location</h1>
                        <input
                            class="border flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md max-md:text-sm max-md:py-1 max-md:px-2"
                            name="branch_loc" type="text" id="branch_loc" autocomplete="off"
                            value="{{ $branch->branch_loc }}" oninput="validateInput('branch_loc')">
                        @error('branch_loc')
                            <span id="branch_loc_error"
                                class="validation-message text-white max-md:text-sm bg-red-600 p-1 rounded-md my-1 show">{{ $message }}</span>
                        @enderror
                    </label>
                </div>
                <div class="w-full flex gap-2 px-8 mb-3">

                    <button
                        class="flex-1 py-2 px-8 max-md:text-xs max-md:py-2 max-md:px-4 font-semibold rounded-md hover:bg-green-600 hover:border-green-600 hover:text-white text-gray-800 border-2 border-gray-600 transition-all"
                        type="submit">
                        Update
                    </button>
                    <button
                        class="flex-1 py-2 max-md:text-xs max-md:py-2 max-md:px-4 px-8 font-semibold rounded-md hover:bg-gray-600 border-2 border-gray-600 hover:text-white text-gray-800  transition-all"
                        type="reset">
                        Reset
                    </button>
                    <a href=" {{ route('branch') }} "
                        class="flex-1 py-2 max-md:text-xs max-md:py-2 max-md:px-4 flex justify-center items-center px-8 font-semibold rounded-md hover:bg-red-600 hover:border-red-600 border-2 border-gray-600 text-gray-800  hover:text-white transition-all"
                        type="reset">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </section>
    <script>
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('appointment_date').setAttribute('min', today);

        function validateInput(field) {
            const input = document.getElementById(field);
            const errorElement = document.getElementById(`${field}_error`);

            // Assuming that the presence of errorElement means the field has an error initially
            if (errorElement) {
                // If the input is valid (e.g., not empty), hide the error message
                if (input.value.trim() !== '') {
                    errorElement.classList.remove('show');
                    errorElement.classList.add('hide');

                    setTimeout(() => {
                        errorElement.style.display = 'none';
                    }, 500);
                } else {
                    // If the input is still invalid, keep the error message visible
                    errorElement.classList.remove('hide');
                    errorElement.classList.add('show');
                }
            }
        }
    </script>
@endsection
