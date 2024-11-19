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

        ul {
            display: flex;
            justify-content: start;
            justify-items: center;
            gap: 8;
            max-width: 50svw;
            flex-wrap: wrap;
        }

        li {
            border: 1px solid gray;
            margin: 2px;
            padding: 4px 8px;
            border-radius: 6px;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const branchSelect = document.querySelector('#branch_id');
            const dentistSelect = document.querySelector('#dentist_id');


            if (branchSelect) {
                branchSelect.addEventListener('change', function() {
                    const branchId = this.value;

                    // Clear dentist and schedule options
                    dentistSelect.innerHTML = '<option value="">Select Dentist</option>';

                    if (branchId) {
                        fetch(`/appointments/add-walk-in/dentists/${branchId}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                data.forEach(dentist => {
                                    const option = document.createElement('option');
                                    option.value = dentist.id;
                                    option.textContent =
                                        `Dr. ${dentist.dentist_last_name } ${dentist.dentist_first_name}`;
                                    dentistSelect.appendChild(option);
                                });
                            })
                            .catch(error => console.error('Error fetching dentists:', error));
                    }
                });
            }

        });
    </script>
    <div class="m-4">
        @include('components.search')
    </div>
    <section class="bg-white shadow-lg rounded-md max-w-max p-6 my-4 mx-auto  max-lg:mt-14">
        <h1 class="font-bold text-4xl px-4 max-md:text-2xl w-max">Add Dentist Schedule</h1>
        <form method="POST" action="{{ route('store.schedule') }}">
            @method('POST')
            @csrf
            <div class="flex flex-col items-start justify-start gap-8 max-w-4xl p-4">
                <div class="w-full ">
                    <label class="flex flex-col flex-1 pb-4 " for="branch_id">
                        <h1 class="">Select Branch</h1>
                        <select class="border flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md max-md:text-xs"
                            id="branch_id" name="branch_id" required>
                            <option class="max-md:text-xs" value="">Select your branch</option>
                            @foreach ($branches as $branch)
                                <option class="max-md:text-xs" value="{{ $branch->id }}">
                                    {{ $branch->branch_loc }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <label class="flex flex-col flex-1 pb-4" for="dentist_id">
                        <h1>Select Dentist</h1>
                        <select class="border max-md:text-xs flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md"
                            id="dentist_id" name="dentist_id" required>
                            <option class="max-md:text-xs" value="">Select branch first</option>
                        </select>

                    </label>
                    <label class="flex flex-col flex-1 pb-4" for="date">Select Date:
                        <input type="date" id="date" name="date"
                            class="border max-md:text-xs flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md" required>
                        <button type="button" id="addDate"
                            class="mt-4 py-2 max-md:text-xs max-md:py-2 max-md:px-4 font-medium rounded-md hover:bg-green-600 hover:border-green-600 hover:text-white text-gray-800 border border-gray-600 transition-all">Add
                            Date</button>
                        <input type="hidden" name="selected_dates" id="selected_dates">
                        <div id="selected_dates_display" class="flex flex-wrap my-4 text-gray-800 rounded-md">
                            Selected dates:
                        </div>
                    </label>
                    <div class="flex flex-wrap flex-1 gap-4 pb-4">
                        <label class="flex flex-col flex-1" for="start_time">
                            <h1>Start Time</h1>
                            <input class="border max-md:text-xs flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md"
                                name="start_time" type="time" id="start_time" step="900" min="09:00" max="17:00">
                            @error('start_time')
                                <span id="start_time_error"
                                    class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                            @enderror
                        </label>
                        <label class="flex flex-col flex-1" for="end_time">
                            <h1>End Time</h1>
                            <input class="border max-md:text-xs flex-grow min-w-max border-gray-400 py-2 px-4 rounded-md"
                                name="end_time" type="time" id="end_time" step="900" min="09:00" max="17:00">
                            @error('end_time')
                                <span id="end_time_error"
                                    class="validation-message text-red-600 text-xs p-1 rounded-md my-1 show">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>
                    <input type="hidden" id="appointment_duration" name="appointment_duration" value="60">
                </div>
                <div class="w-full flex gap-2 mb-3">

                    <button
                        class="flex-1 justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-green-600 hover:border-green-600 hover:text-white text-gray-800 border-2 border-gray-600 transition-all"
                        type="submit">
                        Add
                    </button>
                    <button
                        class="flex-1 justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-gray-600 border-2 border-gray-600 hover:text-white text-gray-800  transition-all"
                        type="reset">
                        Reset
                    </button>
                    <a href=" {{ route('schedule') }} "
                        class="flex-1 justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:px-2 max-md:text-xs font-semibold rounded-md hover:bg-red-600 hover:border-red-600 border-2 border-gray-600 text-gray-800  hover:text-white transition-all"
                        type="reset">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </section>
    <script>
        const today = new Date().toISOString().split('T')[0];
        const dateInput = document.getElementById('date');
        dateInput.setAttribute('min', today);

        // Disable Sundays
        dateInput.addEventListener('input', function(e) {
            const selected = new Date(this.value);
            if (selected.getDay() === 0) { // 0 = Sunday
                alert('Scheduling is not available on Sundays');
                this.value = '';
            }
        });


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


        const selectedDates = [];

        document.getElementById('addDate').addEventListener('click', function() {
            const dateInput = document.getElementById('date');
            const dateValue = dateInput.value;

            if (dateValue && !selectedDates.includes(dateValue)) {
                selectedDates.push(dateValue);
                document.getElementById('selected_dates').value = JSON.stringify(selectedDates);
                updateSelectedDatesDisplay();
            } else {
                alert('Please select a valid date or avoid duplicates.');
            }
        });

        function updateSelectedDatesDisplay() {
            const displayElement = document.getElementById('selected_dates_display');
            displayElement.innerHTML = '';
            if (selectedDates.length > 0) {
                const list = document.createElement('ul');
                selectedDates.forEach(date => {
                    const listItem = document.createElement('li');
                    listItem.textContent = date;
                    list.appendChild(listItem);
                });
                displayElement.appendChild(list);
            } else {
                displayElement.textContent = 'No dates selected.';
            }
        }
    </script>
@endsection
