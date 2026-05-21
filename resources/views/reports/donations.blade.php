<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <a href="{{ route('reports.index') }}" class="text-gray-500 hover:text-red-600 text-sm"><i class="fas fa-arrow-left mr-1"></i>Reports</a>
                <span class="text-gray-400">/</span>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Donations Report</h2>
            </div>
            <a href="{{ route('reports.export.donations', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white shadow rounded-lg p-5 border-l-4 border-red-600">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Total Donations</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalDonations }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-5 border-l-4 border-pink-500">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Total Quantity (ml)</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($totalQuantity) }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-5 border-l-4 border-purple-500">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Avg Quantity (ml)</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($averageQuantity) }}</p>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white shadow rounded-lg p-5">
                <form method="GET" action="{{ route('reports.donations') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Blood Type</label>
                        <select name="blood_type" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-red-500 focus:border-red-500">
                            <option value="">All Types</option>
                            @foreach($bloodTypes as $type)
                                <option value="{{ $type }}" {{ request('blood_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700">
                            <i class="fas fa-filter mr-1"></i>Filter
                        </button>
                        <a href="{{ route('reports.donations') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-600 hover:bg-gray-50">Reset</a>
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
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity (ml)</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Screening</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($donations as $donation)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $donation->id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $donation->donor->full_name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $donation->donation_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-medium">{{ $donation->blood_type }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ number_format($donation->quantity_donated) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $donation->screening_result ?? 'Pending' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ ucfirst($donation->status ?? 'completed') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">No donations found for the selected filters.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-200">
                    {{ $donations->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
