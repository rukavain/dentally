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

        .payment-modal {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* Overlay */
        }

        .modal-dialog {
            position: relative;
            margin: auto;
            top: 20%;
            width: 50%;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
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
        <div class="m-2">
            <h2 class=" font-bold text-2xl mb-2 max-md:text-lg">Appointment Payment for Dr.
                {{ $appointment->dentist->dentist_last_name }} {{ $appointment->dentist->dentist_first_name }}</h2>
            <div class="w-full flex justify-between mb-2 ">
                <div class="w-1/2 text-left">
                    <h4 class="max-md:text-xs mb-1">Patient: </h4>
                    <h4 class="max-md:text-xs mb-1"> Procedure: </h4>
                    <h4 class="max-md:text-xs mb-1"> Appointment Date: </h4>
                    <h4 class="max-md:text-xs mb-1"> Total Amount Due: </h4>
                    <h4 class="max-md:text-xs mb-1"> Balance Remaining: </h4>
                </div>
                <div class=" w-1/2 text-right">
                    <h2 class="max-md:text-xs font-semibold mb-1">
                        {{ $appointment->patient->last_name }} {{ $appointment->patient->first_name }}</h2>
                    <h2 class="max-md:text-xs font-semibold mb-1">
                        {{ $appointment->procedure->name }}</h2>
                    <h2 class="max-md:text-xs font-semibold mb-1">
                        {{ $appointment->appointment_date }}</h2>
                    <h2 class="max-md:text-xs font-semibold mb-1">&#8369;
                        {{ number_format($appointment->procedure->price, 2) }}</h2>
                    <h2 class="max-md:text-xs font-semibold mb-1">&#8369;
                        {{ number_format($balanceRemaining, 2) }}</h2>

                </div>
            </div>
            <div class="flex justify-center">
                <form id="paymentForm" method="POST" action="{{ route('payments.store', $appointment->id) }}">
                    @csrf
                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                    <div class="mt-4">
                        <label for="paid_amount" class="block text-sm font-medium">Amount to Pay:</label>
                        <input type="number" name="paid_amount" id="paid_amount"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-500"
                            required>
                    </div>

                    <div class="mt-4">
                        <label for="payment_method" class="block text-sm font-medium">Payment Method:</label>
                        <select name="payment_method" id="payment_method"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-500"
                            required>
                            <option value="cash">Cash</option>
                            <option value="credit card">Credit Card</option>
                            <option value="bank transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <div class="mt-4">
                        <label for="remarks" class="block text-sm font-medium">Remarks:</label>
                        <textarea name="remarks" id="remarks"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-500"></textarea>
                    </div>
                    <div class="w-full flex justify-between gap-2 mt-4">
                        <button
                            class="flex justify-center items-center  py-2 px-8 text-center max-md:py-2 max-md:text-xs font-semibold rounded-md bg-green-600  text-white transition-all"
                            type="button" id="submitPaymentBtnAdmins">
                            Submit Payment
                        </button>
                        <a href=" {{ route('payments.history', $appointment->id) }} "
                            class="flex justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:text-xs font-semibold rounded-md hover:bg-red-600 hover:border-red-600 border-2 border-gray-600 text-gray-800  hover:text-white transition-all"
                            type="reset">
                            History
                        </a>


                        <a @if (Auth::user()->role === 'admin') href="{{ route('show.appointment', $appointment->id) }}" @else href=" {{ route('staff.dashboard') }} " @endif
                            class="flex justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:text-xs font-semibold rounded-md hover:bg-red-600 hover:border-red-600 border-2 border-gray-600 text-gray-800  hover:text-white transition-all"
                            type="reset">
                            Cancel
                        </a>
                    </div>
                </form>

                <div id="loadingIndicator" style="display: none;" class="loading-indicator">
                    <div class="spinner"></div>
                    <p>Loading, please wait...</p>
                </div>


                <!-- Password Confirmation Modal -->
                <div id="passwordModal" class="payment-modal inset-0 items-center justify-center z-50"
                    style="display: none">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                        <h5 class="text-lg font-bold mb-4">Confirm Your Password</h5>
                        <form id="confirmPasswordForm">
                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium">Password:</label>
                                <input type="password" name="password" id="password"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-500"
                                    required>
                            </div>
                            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                            <div class="flex justify-end">
                                <button type="reset" class="mr-2 bg-gray-300 text-gray-700 px-4 py-2 rounded-md"
                                    id="cancelModalBtn">Cancel</button>
                                <button type="button" class="bg-green-600 text-white px-4 py-2 rounded-md"
                                    id="confirmPayment">Confirm Payment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        // Show the password modal
        document.getElementById('submitPaymentBtnAdmins').addEventListener('click', function() {
            document.getElementById('passwordModal').style.display = "flex";
        });

        // Hide the modal
        document.getElementById('cancelModalBtn').addEventListener('click', function() {
            document.getElementById('passwordModal').style.display = "none";
        });

        // Confirm payment
        // document.getElementById('confirmPayment').addEventListener('click', function() {
        //     const loadingIndicator = document.getElementById('loadingIndicator');
        //     loadingIndicator.style.display = 'flex';

        //     const password = document.getElementById('password').value;
        //     const paidAmount = document.getElementById('paid_amount').value;
        //     const paymentMethod = document.getElementById('payment_method').value;
        //     const remarks = document.getElementById('remarks').value;
        //     const appointmentId = document.querySelector('input[name="appointment_id"]').value;

        //     if (!paidAmount || paidAmount <= 0) {
        //         alert('Please enter a valid amount.');
        //         loadingIndicator.style.display = "none"; // Hide loading indicator
        //         return;
        //     }

        //     // Create a FormData object to send the data
        //     const formData = new FormData();
        //     formData.append('password', password);
        //     formData.append('paid_amount', paidAmount);
        //     formData.append('payment_method', paymentMethod);
        //     formData.append('remarks', remarks);
        //     formData.append('appointment_id', appointmentId);
        //     formData.append('_token', '{{ csrf_token() }}');


        //     // Send the data using Fetch API
        //     fetch('{{ route('payments.store', $appointment->id) }}', {
        //             method: 'POST',
        //             body: formData
        //         })
        //         // .then(response => {
        //         //     loadingIndicator.style.display = 'none'; // Hide loading indicator
        //         //     if (!response.ok) {
        //         //         throw new Error('Network response was not ok ' + response.statusText);
        //         //     }
        //         //     return response.json();
        //         // })
        //         .then(response => response.json())
        //         .then(data => {
        //             if (data.success) {
        //                 // Clear all fields after successful payment
        //                 document.getElementById('paymentForm').reset(); // Reset the form fields
        //                 document.getElementById('password').value = ''; // Clear the password field
        //                 document.getElementById('passwordModal').style.display = "none"; // Hide the modal
        //                 // Handle success (e.g., redirect or show a success message)

        //                 window.location.href = '{{ route('payments.list', $appointment->patient_id) }}';

        //             } else {
        //                 // Handle error (e.g., show an error message)
        //                 alert(data.message);
        //                 document.getElementById('password').value = ''; // Clear the password field
        //                 loadingIndicator.style.display = "none"; // Hide loading indicator
        //             }
        //         })
        //         .catch(error => console.error('Error:', error));
        // });

        document.getElementById('confirmPayment').addEventListener('click', function() {
            const loadingIndicator = document.getElementById('loadingIndicator');
            loadingIndicator.style.display = 'flex';

            const password = document.getElementById('password').value;
            const paidAmount = document.getElementById('paid_amount').value;
            const paymentMethod = document.getElementById('payment_method').value;
            const remarks = document.getElementById('remarks').value;
            const appointmentId = document.querySelector('input[name="appointment_id"]').value;

            if (!paidAmount || paidAmount <= 0) {
                alert('Please enter a valid amount.');
                loadingIndicator.style.display = "none"; // Hide loading indicator
                return;
            }

            // Create a FormData object to send the data
            const formData = new FormData();
            formData.append('password', password);
            formData.append('paid_amount', paidAmount);
            formData.append('payment_method', paymentMethod);
            formData.append('remarks', remarks);
            formData.append('appointment_id', appointmentId);
            formData.append('_token', '{{ csrf_token() }}');


            // Send the data using Fetch API
            fetch('{{ route('payments.store', $appointment->id) }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Clear all fields after successful payment
                        document.getElementById('paymentForm').reset(); // Reset the form fields
                        document.getElementById('password').value = ''; // Clear the password field
                        document.getElementById('passwordModal').style.display = "none"; // Hide the modal
                        // Handle success (e.g., redirect or show a success message)
                        window.location.href = '{{ route('payments.list', $appointment->patient_id) }}';


                    } else {
                        // Handle error (e.g., show an error message)
                        alert(data.message);
                        document.getElementById('password').value = ''; // Clear the password field
                        loadingIndicator.style.display = "none"; // Hide loading indicator
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
@endsection
