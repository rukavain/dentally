@extends('client.profile')
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

        .upload-modal {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            /* Overlay */
        }

        .upload-modal-dialog {
            position: relative;
            margin: auto;
            top: 20%;
            width: 50%;
        }

        .upload-modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
        }

        .image-modal {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: flex;
            justify-content: center;
            overflow-y: auto;
            background-size: contain;
            /* Overlay */
        }
    </style>

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
                    <h4 class="max-md:text-xs mb-1"> Remaining Balance: </h4>
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
            <div class="flex flex-col justify-center">
                <form id="paymentForm" method="POST" action="{{route('client.pay', $appointment->id)}}">
                    @method('POST')
                    @csrf
                    <div class="">
                        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                        <div class="mt-4">
                            <label for="paid_amount" class="block text-sm font-medium">Amount to Pay:</label>
                            <input type="number" name="paid_amount" id="paid_amount" min="100"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-500"
                                required>
                        </div>

                        <div class="mt-4">
                            <label for="payment_method" class="block text-sm font-medium">Payment Method:</label>
                            <select name="payment_method" id="payment_method"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-500"
                                required onchange="togglePaymentOptions()">
                                <option value="gcash">Gcash</option>
                                <option value="maya">Maya</option>
                                <option value="card">Card</option>
                            </select>
                        </div>


                        <div class="mt-4">
                            <label for="remarks" class="block text-sm font-medium">Remarks:</label>
                            <textarea name="remarks" id="remarks"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-500"></textarea>
                        </div>
                        <div id="payment_proof_section"  class="my-4">
                            <label for="payment_proof" class="block text-sm font-medium">Upload Proof of Payment:</label>
                            <input type="file" name="payment_proof" id="payment_proof" accept="image/*"
                                class="mt-1 block  border p-2 text-sm w-full rounded-md shadow-sm focus:ring focus:ring-green-500"
                                >
                        </div>

                    </div>
                    <div class="w-full flex justify-between gap-2 mt-4">
                        <div id="redirect_button_section" class="w-full gap-2 mt-4 hidden">
                            <button type="submit" class="w-full flex justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:text-xs font-semibold rounded-md bg-green-600 text-white transition-all">
                                Redirect to Paymongo
                            </button>
                        </div>

                        <div id="proof_button_section" class="w-full flex justify-between gap-2 mt-4">
                        <button
                            class="flex justify-center items-center  py-2 px-8 text-center max-md:py-2 max-md:text-xs font-semibold rounded-md bg-green-600  text-white transition-all"
                            type="button" id="submitPaymentBtnClient">
                            Submit Payment
                        </button>
                        <a href=" {{ route('client.history', $appointment->id) }} "
                            class="flex justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:text-xs font-semibold rounded-md hover:bg-red-600 hover:border-red-600 border-2 border-gray-600 text-gray-800  hover:text-white transition-all"
                            type="reset">
                            History
                        </a>

                        <a href=" {{ route('client.overview', $appointment->patient_id) }} "
                            class="flex justify-center items-center py-2 px-8 text-center max-md:py-2 max-md:text-xs font-semibold rounded-md hover:bg-red-600 hover:border-red-600 border-2 border-gray-600 text-gray-800  hover:text-white transition-all"
                            type="reset">
                            Cancel
                        </a>
                        </div>
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
                                <button type="button" class="mr-2 bg-gray-300 text-gray-700 px-4 py-2 rounded-md"
                                    id="cancelModalBtn">Cancel</button>
                                <button type="button" class="bg-green-600 text-white px-4 py-2 rounded-md"
                                    id="confirmClientPayment">Confirm Payment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        const paidAmountInput = document.getElementById('paid_amount');
        const paymentProofInput = document.getElementById('payment_proof');
        const submitButton = document.getElementById('submitPaymentBtnClient');

        function toggleSubmitButton() {
            const isPaidAmountValid = paidAmountInput.value > 0; // Check if paid amount is greater than 0
            const isPaymentProofValid = paymentProofInput.files.length > 0; // Check if a file is selected

            // Enable the button only if both conditions are met
            submitButton.disabled = !(isPaidAmountValid && isPaymentProofValid);
        }

        // Add event listeners to the inputs
        paidAmountInput.addEventListener('input', toggleSubmitButton);
        paymentProofInput.addEventListener('change', toggleSubmitButton);

        // Initial check to set the button state on page load
        toggleSubmitButton();
        // Get modal and buttons

        document.getElementById('submitPaymentBtnClient').addEventListener('click', function() {
            document.getElementById('passwordModal').style.display = "flex";
        });

        document.getElementById('cancelModalBtn').addEventListener('click', function() {
            document.getElementById('passwordModal').style.display = "none";
        });

        document.getElementById('confirmClientPayment').addEventListener('click', function() {
            const loadingIndicator = document.getElementById('loadingIndicator');
            loadingIndicator.style.display = 'flex';

            const password = document.getElementById('password').value;
            const paidAmount = document.getElementById('paid_amount').value;
            const paymentMethod = document.getElementById('payment_method').value;
            const remarks = document.getElementById('remarks').value;
            const paymentProof = document.getElementById('payment_proof').files[0];
            const appointmentId = document.querySelector('input[name="appointment_id"]').value;

            if (!paidAmount || paidAmount <= 0) {
                alert('Please enter a valid amount.');
                loadingIndicator.style.display = "none"; // Hide loading indicator
                return;
            }

            if (!paymentProof) {
                alert('Please upload proof of payment.');
                loadingIndicator.style.display = "none"; // Hide loading indicator
                return;
            }

            // Create a FormData object to send the data
            const formData = new FormData();
            formData.append('password', password);
            formData.append('paid_amount', paidAmount);
            formData.append('payment_method', paymentMethod);
            formData.append('remarks', remarks);
            formData.append('payment_proof', paymentProof);
            formData.append('appointment_id', appointmentId);
            formData.append('_token', '{{ csrf_token() }}');

            // Send the data using Fetch API
            fetch('{{ route('client.store', $appointment->id) }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Clear all fields after successful payment
                        document.getElementById('paymentForm').reset();
                        document.getElementById('password').value = '';
                        document.getElementById('passwordModal').style.display = "none";

                        // Redirect based on user role (client in this case)
                        window.location.href = '{{ route('client.overview', $appointment->patient_id) }}';
                    } else {
                        alert(data.message);
                        document.getElementById('password').value = ''; // Clear the password field
                        loadingIndicator.style.display = "none"; // Hide loading indicator
                    }
                })
                .catch(error => console.error('Error:', error));
        });


        function togglePaymentOptions() {
        const paymentMethod = document.getElementById('payment_method').value;
        const paymentProofSection = document.getElementById('payment_proof_section');
        const redirectButtonSection = document.getElementById('redirect_button_section');
        const proofButtonSection = document.getElementById('proof_button_section');

        if (paymentMethod === 'card') {
            paymentProofSection.classList.add('hidden');
            proofButtonSection.classList.add('hidden');
            redirectButtonSection.classList.remove('hidden');
        } else {
            paymentProofSection.classList.remove('hidden');
            proofButtonSection.classList.remove('hidden');
            redirectButtonSection.classList.add('hidden');
        }
    }
    </script>
@endsection
