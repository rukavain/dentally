@extends('admin.dashboard')
@section('content')
    <div class="m-2">
        @include('components.search')
    </div>
    <section class="bg-white shadow-lg rounded-md py-2 px-3 mx-2 max-lg:mt-14">
        <div class="flex justify-between items-center mb-3">
            <h1 class="font-bold text-xl max-md:text-lg">Audit Logs</h1>

        </div>

        <div class="overflow-x-auto">
            <table class="w-full table text-left text-wrap border-collapse">
                <thead class="bg-green-100 text-green-700">
                    <tr>
                        <th class="px-2 py-1.5 border text-xs font-medium">User Email</th>
                        <th class="px-2 py-1.5 border text-xs font-medium">Action</th>
                        <th class="px-2 py-1.5 border text-xs font-medium max-md:hidden">Date</th>
                        <th class="px-2 py-1.5 border text-xs font-medium">Subject</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @foreach ($auditLogs as $log)
                        <tr class="hover:bg-gray-50 border-b transition-colors">
                            <td class="px-2 py-1.5 border max-w-[180px] break-words">
                                {{ $log->user_email }}
                            </td>
                            <td class="px-2 py-1.5 border">
                                <span
                                    @if ($log->action === 'Create') class="inline-flex items-center bg-green-50 text-green-700 text-xs px-2 py-0.5 rounded-full"
                                    @elseif($log->action === 'Update') class="inline-flex items-center bg-blue-50 text-blue-700 text-xs px-2 py-0.5 rounded-full"
                                    @elseif($log->action === 'Delete') class="inline-flex items-center bg-red-50 text-red-700 text-xs px-2 py-0.5 rounded-full"
                                    @elseif($log->action === 'Upload') class="inline-flex items-center bg-violet-50 text-violet-700 text-xs px-2 py-0.5 rounded-full"
                                    @elseif($log->action === 'Payment') class="inline-flex items-center bg-slate-50 text-slate-700 text-xs px-2 py-0.5 rounded-full" @endif>
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-2 py-1.5 border text-gray-600 max-md:hidden whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y H:i') }}
                            </td>
                            <td class="px-2 py-1.5 border text-gray-600">
                                {{ str_replace('App\\Models\\', '', $log->model_type) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-2 text-sm">
            {{ $auditLogs->links() }}
        </div>
    </section>
@endsection
