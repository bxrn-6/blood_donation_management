<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('reports.index') }}" class="text-gray-500 hover:text-red-600 text-sm"><i class="fas fa-arrow-left mr-1"></i>Reports</a>
            <span class="text-gray-400">/</span>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Overview</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Key Metrics --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white shadow rounded-lg p-4 border-t-4 border-red-600 text-center">
                    <p class="text-2xl font-bold text-gray-800">{{ $totalDonors }}</p>
                    <p class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Total Donors</p>
                </div>
                <div class="bg-white shadow rounded-lg p-4 border-t-4 border-green-500 text-center">
                    <p class="text-2xl font-bold text-gray-800">{{ $activeDonors }}</p>
                    <p class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Active Donors</p>
                </div>
                <div class="bg-white shadow rounded-lg p-4 border-t-4 border-pink-500 text-center">
                    <p class="text-2xl font-bold text-gray-800">{{ $totalDonations }}</p>
                    <p class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Total Donations</p>
                </div>
                <div class="bg-white shadow rounded-lg p-4 border-t-4 border-purple-500 text-center">
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($totalQuantity) }}</p>
                    <p class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Total ml</p>
                </div>
                <div class="bg-white shadow rounded-lg p-4 border-t-4 border-yellow-500 text-center">
                    <p class="text-2xl font-bold text-gray-800">{{ $totalRequests }}</p>
                    <p class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Total Requests</p>
                </div>
                <div class="bg-white shadow rounded-lg p-4 border-t-4 border-blue-500 text-center">
                    <p class="text-2xl font-bold text-gray-800">{{ $fulfilledRequests }}</p>
                    <p class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Fulfilled</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Donations by Blood Type --}}
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-800"><i class="fas fa-tint mr-2 text-red-600"></i>Donations by Blood Type</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Blood Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Donations</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total (ml)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($donationsByBloodType as $row)
                                    <tr>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold">{{ $row->blood_type }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $row->count }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ number_format($row->total) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500">No data available.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Inventory by Blood Type --}}
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-800"><i class="fas fa-flask mr-2 text-blue-500"></i>Available Inventory by Blood Type</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Blood Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Available (ml)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($inventoryByBloodType as $row)
                                    <tr>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold">{{ $row->blood_type }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ number_format($row->total) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="px-4 py-6 text-center text-sm text-gray-500">No inventory data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Recent Donations --}}
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-base font-semibold text-gray-800"><i class="fas fa-hand-holding-heart mr-2 text-pink-500"></i>Recent Donations</h3>
                        <a href="{{ route('reports.donations') }}" class="text-xs text-red-600 hover:underline">View All</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($recentDonations as $donation)
                            <div class="px-5 py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $donation->donor->full_name ?? 'Unknown Donor' }}</p>
                                    <p class="text-xs text-gray-500">{{ $donation->donation_date->format('M d, Y') }}</p>
                                </div>
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold">{{ $donation->blood_type }}</span>
                            </div>
                        @empty
                            <p class="px-5 py-6 text-sm text-gray-500 text-center">No recent donations.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Recent Requests --}}
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-base font-semibold text-gray-800"><i class="fas fa-hand-holding-medical mr-2 text-yellow-500"></i>Recent Blood Requests</h3>
                        <a href="{{ route('reports.requests') }}" class="text-xs text-red-600 hover:underline">View All</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($recentRequests as $request)
                            <div class="px-5 py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $request->hospital->name ?? 'Unknown Hospital' }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($request->request_date)->format('M d, Y') }}</p>
                                </div>
                                <span class="px-2 py-1 rounded text-xs font-medium
                                    {{ $request->status === 'Fulfilled' ? 'bg-green-100 text-green-700' :
                                       ($request->status === 'Pending' ? 'bg-yellow-100 text-yellow-700' :
                                       ($request->status === 'Approved' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700')) }}">
                                    {{ $request->status }}
                                </span>
                            </div>
                        @empty
                            <p class="px-5 py-6 text-sm text-gray-500 text-center">No recent requests.</p>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- Monthly Donations Trend --}}
            @if($monthlyDonations->count())
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-800"><i class="fas fa-chart-line mr-2 text-purple-500"></i>Monthly Donations (Last 6 Months)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Donations</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($monthlyDonations as $row)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ \Carbon\Carbon::createFromDate($row->year, $row->month, 1)->format('F Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ $row->count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
