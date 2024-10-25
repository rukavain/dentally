@extends('admin.dashboard')
@section('content')
    <div class="m-4 ">
        @include('components.search')
    </div>
    <section class="bg-white shadow-lg rounded-md py-2 px-4 mx-2 max-lg:mt-14">
        <h1 class="font-bold text-2xl p-4 max-md:text-3xl">Audit Logs</h1>

        <table class="w-full table text-left text-wrap">
            <thead class="bg-green-200 text-green-700">
                <tr class="border-b">
                    <th class="px-4 py-2 border max-xl:text-sm">User Email</th>
                    <th class="px-4 py-2 border max-xl:text-sm">Action</th>
                    <th class="px-4 py-2 border max-xl:text-sm max-md:hidden">Date</th>
                    <th class="px-4 py-2 border max-xl:text-sm">Subject</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($auditLogs as $log)
                    <tr class="hover:bg-gray-100 border-b-2 last:border-b-0 ">
                        <td class="px-2 py-2 text-xs
                         max-w-16 break-words">
                            {{ $log->user_email }}</td>
                        <td class="px-4 py-2 text-xs">
                            <span
                                @if ($log->action === 'Create') class="bg-green-200 text-green-700 px-4 rounded-full"
                                @elseif($log->action === 'Update') class="bg-blue-200 text-blue-700 px-4 rounded-full"
                                @elseif($log->action === 'Delete') class="bg-red-200 text-red-700 px-4 rounded-full"
                                @elseif($log->action === 'Upload') class="bg-violet-200 text-violet-700 px-4 rounded-full"
                                @elseif($log->action === 'Payment') class="bg-slate-200 text-slate-700 px-4 rounded-full" @endif>
                                <span class="max-md:hidden">&#9679;</span> {{ $log->action }} </span>
                            <span>
                                <ul class="flex flex-wrap font-normal text-xs">
                                    {{-- @if (isset($log->changes['message']))
                                        <li>{{ $log->changes['message'] }}</li>
                                    @else
                                        @foreach ($log->changes as $key => $value)
                                            <li><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</li>
                                        @endforeach
                                    @endif --}}
                                </ul>
                            </span>
                        </td>
                        <td class="px-4 py-2 text-xs max-md:hidden">{{ $log->created_at }}</td>
                        <td class="px-4 py-2 text-xs max-w-min ">{{ $log->model_type }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-2">
            {{ $auditLogs->links() }}
        </div>
    </section>
@endsection
