<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <a href="{{ route('reports.index') }}" class="text-gray-500 hover:text-red-600 text-sm"><i class="fas fa-arrow-left mr-1"></i>Reports</a>
                <span class="text-gray-400">/</span>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Inventory Report</h2>
            </div>
            <a href="{{ route('reports.export.inventory', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Blood Type Summary Grid --}}
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-800">Blood Type Summary</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total (ml)</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Low</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expired</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($summary as $bloodType => $data)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium">
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold">{{ $bloodType }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 font-semibold">{{ number_format($data['total']) }}</td>
                                    <td class="px-4 py-3 text-sm text-green-700 font-medium">{{ number_format($data['available']) }}</td>
                                    <td class="px-4 py-3 text-sm text-yellow-600 font-medium">{{ number_format($data['low']) }}</td>
                                    <td class="px-4 py-3 text-sm text-red-600 font-medium">{{ number_format($data['expired']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white shadow rounded-lg p-5">
                <form method="GET" action="{{ route('reports.inventory') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Blood Type</label>
                        <select name="blood_type" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-red-500 focus:border-red-500">
                            <option value="">All Types</option>
                            @foreach($bloodTypes as $type)
                                <option value="{{ $type }}" {{ request('blood_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
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
                    <div class="flex space-x-2">
                        <button type="submit" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700">
                            <i class="fas fa-filter mr-1"></i>Filter
                        </button>
                        <a href="{{ route('reports.inventory') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-600 hover:bg-gray-50">Reset</a>
                    </div>
                </form>
            </div>

            {{-- Inventory Table --}}
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity (ml)</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($inventory as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->id }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold">{{ $item->blood_type }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ number_format($item->quantity) }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($item->status === 'Available')
                                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">Available</span>
                                        @elseif($item->status === 'Low')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-medium">Low</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-medium">Expired</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $item->expiry_date ? \Carbon\Carbon::parse($item->expiry_date)->format('M d, Y') : 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">No inventory records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
