@extends('admin.dashboard')
@section('content')
    <div class="m-4">
        @include('components.search')
    </div>

    <section class="bg-white m-4 p-8 shadow-lg rounded-md flex flex-col justify-center z-0">
        <h1 class="text-5xl font-bold mb-6">Update Payment for {{ $patient->first_name }} {{ $patient->last_name }}</h1>

        <form action="{{ route('update.payment', [$patient->id, $payment->id]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="flex justify-between gap-4 my-5 py-2">
                <label for="tooth_number" class="flex flex-col">
                    <span>Tooth Number</span>
                    <input type="text" name="tooth_number" id="tooth_number"
                        class="border bg-slate-100 border-gray-400 py-2 px-4 rounded-md" value="{{ $payment->tooth_number }}" disabled>
                </label>
                <label for="dentist" class="flex flex-col">
                    <span>Dentist</span>
                    <input type="text" name="dentist" id="dentist" class="border bg-slate-100 border-gray-400 py-2 px-4 rounded-md"
                    value="{{ $payment->dentist }}" disabled>
                </label>
                <label for="procedure" class="flex flex-col">
                    <span>Procedure</span>
                    <input type="text" name="procedure" id="procedure"
                        class="border bg-slate-100 border-gray-400 py-2 px-4 rounded-md" value="{{ $payment->procedure }}" disabled>
                </label>
                <label for="charge" class="flex flex-col">
                    <span>Charge</span>
                    <input type="number" name="charge" id="charge" class="border bg-slate-100 border-gray-400 py-2 px-4 rounded-md"
                    value="{{ $payment->charge }}" disabled>
                </label>
                <label for="balance_remaining" class="flex flex-col">
                    <span>Balance Remaining</span>
                    <input type="number" name="balance_remaining" id="balance_remaining" class="border bg-slate-100 border-gray-400 py-2 px-4 rounded-md"
                    value="{{ $payment->balance_remaining }}" disabled>
                </label>
            </div>    

            <div class="mb-4">
                <label for="paid" class="block text-sm font-medium text-gray-700">Amount Paid</label>
                <input type="number" name="paid" id="paid"
                    class="mt-1 border border-gray-600 p-2 rounded-md block w-full" value="{{ old('paid') }}" required>
            </div>
            <div class="mb-4">
                <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                <textarea name="remarks" id="remarks" rows="4" class="mt-1 border border-gray-600 p-2 rounded-md block w-full">{{ old('remarks', $payment->remarks) }}</textarea>
            </div>
            <div class="mb-4">
                <label for="signature" class="block text-sm font-medium text-gray-700">Signature</label>
                <input type="hidden" name="signature" value="0"> <!-- Hidden field to handle unchecked case -->
                <input type="checkbox" name="signature" id="signature" value="1"
                    {{ old('signature', $payment->signature) ? 'checked' : '' }}>
            </div>
            <button type="submit" class="mt-4 py-2 px-4 hover:bg-blue-600 border-2 border-blue-600 hover:text-white rounded-md transition-all">Update Payment</button>
            <a href="{{ route('show.patient', $patient->id) }}"
                class="mt-4 py-2 px-4 hover:bg-red-600 border-2 border-red-600 hover:text-white rounded-md transition-all">
                Cancel</a>
        </form>
    </section>
@endsection
