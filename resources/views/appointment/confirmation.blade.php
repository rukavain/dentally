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
                <label for="branch_id">Branch:</label>
                <select name="branch_id" id="branch_id" class="form-control" required>
                    <option value="">-- Select Branch --</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->branch_loc }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Select Patient -->
            <div class="form-group">
                <label for="patient_id">Patient:</label>
                <select name="patient_id" id="patient_id" class="form-control" required>
                    <option value="">-- Select Patient --</option>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->last_name }} - {{ $patient->first_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Select Procedure -->
            <div class="form-group">
                <label for="proc_id">Procedure:</label>
                <select name="proc_id" id="proc_id" class="form-control" required>
                    <option value="">-- Select Procedure --</option>
                    @foreach ($procedures as $procedure)
                        <option value="{{ $procedure->id }}" {{ old('proc_id') == $procedure->id ? 'selected' : '' }}>
                            {{ $procedure->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Select Dentist -->
            <div class="form-group">
                <label for="dentist_id">Dentist:</label>
                <select name="dentist_id" id="dentist_id" class="form-control" required>
                    <option value="">-- Select Dentist --</option>
                    <!-- Options will be populated based on selected branch -->
                </select>
            </div>

            <!-- Select Schedule -->
            <div class="form-group">
                <label for="schedule_id">Schedule:</label>
                <select name="schedule_id" id="schedule_id" class="form-control" required>
                    <option value="">-- Select Schedule --</option>
                    <!-- Options will be populated based on selected dentist and branch -->
                </select>
            </div>

            <!-- Appointment Date -->
            <div class="form-group">
                <label for="appointment_date">Appointment Date:</label>
                <input type="date" name="appointment_date" id="appointment_date" class="form-control"
                    value="{{ old('appointment_date') }}" required>
            </div>

            <!-- Preferred Time -->
            <div class="form-group">
                <label for="preferred_time">Preferred Time:</label>
                <input type="time" name="preferred_time" id="preferred_time" class="form-control"
                    value="{{ old('preferred_time') }}" required>
            </div>

            <!-- Is Online -->
            <div class="form-group form-check">
                <input type="checkbox" name="is_online" id="is_online" class="form-check-input" value="1"
                    {{ old('is_online') ? 'checked' : '' }}>
                <label for="is_online" class="form-check-label">Is this an online appointment request?</label>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Book Appointment</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const branchSelect = document.getElementById('branch_id');
            const dentistSelect = document.getElementById('dentist_id');
            const scheduleSelect = document.getElementById('schedule_id');

            // Fetch Dentists based on selected branch
            branchSelect.addEventListener('change', function() {
                const branchId = this.value;
                dentistSelect.innerHTML = '<option value="">-- Select Dentist --</option>';
                scheduleSelect.innerHTML = '<option value="">-- Select Schedule --</option>';

                if (branchId) {
                    fetch(`/appointments/get-dentists/${branchId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(dentist => {
                                const option = document.createElement('option');
                                option.value = dentist.id;
                                option.textContent =
                                    `${dentist.dentist_first_name} - ${dentist.dentist_last_name}`;
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
                scheduleSelect.innerHTML = '<option value="">-- Select Schedule --</option>';

                if (branchId && dentistId) {
                    fetch(`/appointments/get-schedules/${branchId}/${dentistId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(schedule => {
                                const option = document.createElement('option');
                                option.value = schedule.id;
                                option.textContent =
                                    `${schedule.date} | ${schedule.start_time} - ${schedule.end_time}`;
                                scheduleSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching schedules:', error));
                }
            });
        });
    </script>
@endsection
