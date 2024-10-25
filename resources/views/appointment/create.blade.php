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
    <div class="m-4 mb-8">
        @include('components.search')
    </div>
    <div class="container">
        <h2>Book an Appointment</h2>

        <!-- Display Success Message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('appointments.store') }}" method="POST" id="appointment-form">
            @csrf

            <!-- Select Branch -->
            <div class="form-group">
                <label for="branch">Branch:</label>
                <select name="branch_id" id="branch" class="form-control" required>
                    <option value="">Select Branch</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->branch_loc }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Select Patient -->
            <div class="form-group">
                <label for="patient">Patient:</label>
                <select name="patient_id" id="patient" class="form-control" required>
                    <option value="">Select Patient</option>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->last_name . ', ' . $patient->first_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Select Dentist -->
            <div class="form-group">
                <label for="dentist">Dentist:</label>
                <select name="dentist_id" id="dentist" class="form-control" required>
                    <option value="">Select Dentist</option>
                    <!-- Options will be loaded dynamically based on selected branch -->
                </select>
            </div>

            <!-- Select Schedule -->
            <div class="form-group">
                <label for="schedule">Schedule:</label>
                <select name="schedule_id" id="schedule" class="form-control" required>
                    <option value="">Select Schedule</option>
                    <!-- Options will be loaded dynamically based on selected dentist and branch -->
                </select>
            </div>
            <div class="form-group">
                <label for="appointment_date">Date:</label>
                <input type="date" name="appointment_date" id="appointment_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="preferred_time">Time:</label>
                <input type="time" name="preferred_time" id="preferred_time" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Book Appointment</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const branchSelect = document.getElementById('branch');
            const dentistSelect = document.getElementById('dentist');
            const scheduleSelect = document.getElementById('schedule');

            // Fetch Dentists based on selected branch
            branchSelect.addEventListener('change', function() {
                const branchId = this.value;
                dentistSelect.innerHTML = '<option value="">Select Dentist</option>';
                scheduleSelect.innerHTML = '<option value="">Select Schedule</option>';

                if (branchId) {
                    fetch(`/api/branches/${branchId}/dentists`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(dentist => {
                                const option = document.createElement('option');
                                option.value = dentist.id;
                                option.textContent = dentist.name;
                                dentistSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching dentists:', error));
                }
            });

            // Fetch Schedules based on selected branch and dentist
            dentistSelect.addEventListener('change', function() {
                const branchId = branchSelect.value;
                const dentistId = this.value;
                scheduleSelect.innerHTML = '<option value="">Select Schedule</option>';

                if (branchId && dentistId) {
                    fetch(`/api/branches/${branchId}/dentists/${dentistId}/schedules`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(schedule => {
                                const option = document.createElement('option');
                                option.value = schedule.id;
                                const date = new Date(schedule.date).toLocaleDateString();
                                option.textContent =
                                    `${date} ${schedule.start_time} - ${schedule.end_time}`;
                                scheduleSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching schedules:', error));
                }
            });
        });
    </script>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const branchSelect = document.getElementById('branch');
            const dentistSelect = document.getElementById('dentist');
            const scheduleSelect = document.getElementById('schedule');

            // Fetch Dentists based on selected branch
            branchSelect.addEventListener('change', function() {
                const branchId = this.value;
                dentistSelect.innerHTML = '<option value="">Select Dentist</option>';
                scheduleSelect.innerHTML = '<option value="">Select Schedule</option>';

                if (branchId) {
                    fetch(`/api/branches/${branchId}/dentists`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(dentist => {
                                const option = document.createElement('option');
                                option.value = dentist.id;
                                option.textContent = dentist.first_name;
                                dentistSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching dentists:', error));
                }
            });

            // Fetch Schedules based on selected branch and dentist
            dentistSelect.addEventListener('change', function() {
                const branchId = branchSelect.value;
                const dentistId = this.value;
                scheduleSelect.innerHTML = '<option value="">Select Schedule</option>';

                if (branchId && dentistId) {
                    fetch(`/api/branches/${branchId}/dentists/${dentistId}/schedules`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(schedule => {
                                const option = document.createElement('option');
                                option.value = schedule.id;
                                const date = new Date(schedule.date).toLocaleDateString();
                                option.textContent =
                                    `${date} ${schedule.start_time} - ${schedule.end_time}`;
                                scheduleSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching schedules:', error));
                }
            });
        });
    </script>
@endsection
