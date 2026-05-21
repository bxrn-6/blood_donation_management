<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('reports.index') }}" class="text-gray-500 hover:text-red-600 text-sm"><i class="fas fa-arrow-left mr-1"></i>Reports</a>
            <span class="text-gray-400">/</span>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Donor Matches Report</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white shadow rounded-lg p-5 border-l-4 border-green-600">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Total Matches</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalMatches }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-5 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Accepted</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $acceptedMatches }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-5 border-l-4 border-red-500">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Declined</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $declinedMatches }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-5 border-l-4 border-purple-500">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Acceptance Rate</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $acceptanceRate }}%</p>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white shadow rounded-lg p-5">
                <form method="GET" action="{{ route('reports.donor-matches') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
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
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700">
                            <i class="fas fa-filter mr-1"></i>Filter
                        </button>
                        <a href="{{ route('reports.donor-matches') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-600 hover:bg-gray-50">Reset</a>
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
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hospital</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matched At</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($donorMatches as $match)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $match->id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $match->donor->full_name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $match->bloodRequest->hospital->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold">
                                            {{ $match->bloodRequest->blood_type ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $match->matched_at ? \Carbon\Carbon::parse($match->matched_at)->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($match->status === 'accepted')
                                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">Accepted</span>
                                        @elseif($match->status === 'declined')
                                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-medium">Declined</span>
                                        @else
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-medium">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">No donor matches found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-200">
                    {{ $donorMatches->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
