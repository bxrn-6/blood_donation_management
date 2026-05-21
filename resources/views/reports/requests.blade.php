<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <a href="{{ route('reports.index') }}" class="text-gray-500 hover:text-red-600 text-sm"><i class="fas fa-arrow-left mr-1"></i>Reports</a>
                <span class="text-gray-400">/</span>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Blood Requests Report</h2>
            </div>
            <a href="{{ route('reports.export.requests', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white shadow rounded-lg p-5 border-l-4 border-yellow-500">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Total Requests</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalRequests }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-5 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Fulfilled</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $fulfilledRequests }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-5 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Pending</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $pendingRequests }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-5 border-l-4 border-purple-500">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Fulfillment Rate</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $fulfillmentRate }}%</p>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white shadow rounded-lg p-5">
                <form method="GET" action="{{ route('reports.requests') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-red-500 focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-red-500 focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-red-500 focus:border-red-500">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urgency</label>
                        <select name="urgency_level" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-red-500 focus:border-red-500">
                            <option value="">All Levels</option>
                            @foreach($urgencyLevels as $level)
                                <option value="{{ $level }}" {{ request('urgency_level') == $level ? 'selected' : '' }}>{{ ucfirst($level) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700">
                            <i class="fas fa-filter mr-1"></i>Filter
                        </button>
                        <a href="{{ route('reports.requests') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-600 hover:bg-gray-50">Reset</a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hospital</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgency</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bloodRequests as $request)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $request->id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $request->hospital->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ \Carbon\Carbon::parse($request->request_date)->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold">{{ $request->blood_type }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $request->quantity_requested }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @php $urgency = strtolower($request->urgency_level ?? ''); @endphp
                                        @if($urgency === 'critical')
                                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-medium">Critical</span>
                                        @elseif($urgency === 'high')
                                            <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded text-xs font-medium">High</span>
                                        @elseif($urgency === 'medium')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-medium">Medium</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs font-medium">Low</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($request->status === 'Fulfilled')
                                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">Fulfilled</span>
                                        @elseif($request->status === 'Approved')
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">Approved</span>
                                        @elseif($request->status === 'Pending')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-medium">Pending</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-medium">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">No blood requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-200">
                    {{ $bloodRequests->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
