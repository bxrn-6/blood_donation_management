<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Hospital Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <h3 class="text-sm font-medium text-gray-500">Your Requests</h3>
                    <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $totalRequests }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <h3 class="text-sm font-medium text-gray-500">Pending Requests</h3>
                    <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $pendingRequests }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <h3 class="text-sm font-medium text-gray-500">Approved Requests</h3>
                    <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $approvedRequests }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <h3 class="text-sm font-medium text-gray-500">Fulfilled Requests</h3>
                    <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $fulfilledRequests }}</p>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <form method="GET" action="{{ route('hospital.dashboard') }}" class="space-y-4">
                    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Blood Requests</h3>
                            <p class="text-sm text-gray-500">Submit and track all your hospital blood requests.</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                            <a href="{{ route('blood-requests.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">New Request</a>
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-900">Search</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Search</label>
                            <input type="text" name="request_search" value="{{ request('request_search') }}" placeholder="Search requests..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                <option value="">All</option>
                                <option value="Pending" @if(request('status') === 'Pending') selected @endif>Pending</option>
                                <option value="Approved" @if(request('status') === 'Approved') selected @endif>Approved</option>
                                <option value="Rejected" @if(request('status') === 'Rejected') selected @endif>Rejected</option>
                                <option value="Fulfilled" @if(request('status') === 'Fulfilled') selected @endif>Fulfilled</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Blood Type</label>
                            <select name="blood_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                <option value="">All</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                    <option value="{{ $type }}" @if(request('blood_type') === $type) selected @endif>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hospital</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgency</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bloodRequests as $request)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $request->request_id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $request->hospital_name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $request->blood_type_needed }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $request->quantity_requested }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $request->request_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ ucfirst($request->urgency_level) }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold @if($request->status === 'Pending') bg-yellow-100 text-yellow-800 @elseif($request->status === 'Approved') bg-blue-100 text-blue-800 @elseif($request->status === 'Fulfilled') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">{{ $request->status }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <a href="{{ route('blood-requests.show', $request->id) }}" class="text-red-600 hover:text-red-900">Details</a>
                                    </td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td colspan="8" class="px-4 py-4">
                                        <div class="rounded-lg bg-white border border-gray-200 p-4">
                                            <div class="flex items-center justify-between mb-4">
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-900">Matching Donors</h4>
                                                    <p class="text-sm text-gray-500">Donors available for {{ $request->blood_type_needed }} blood. Required units: {{ $request->quantity_requested }}.</p>
                                                </div>
                                            </div>
                                            @if($matchingDonors[$request->id]->isEmpty())
                                                <p class="text-sm text-gray-500">No matching donors available yet.</p>
                                            @else
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-100">
                                                            <tr>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor Name</th>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor Blood Type</th>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested Type</th>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units Needed</th>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Availability</th>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Donation</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-200">
                                                            @foreach($matchingDonors[$request->id] as $donor)
                                                                <tr>
                                                                    <td class="px-3 py-2 text-sm text-gray-900">{{ $donor->full_name }}</td>
                                                                    <td class="px-3 py-2 text-sm text-gray-900">{{ $donor->blood_type }}</td>
                                                                    <td class="px-3 py-2 text-sm text-gray-900">{{ $request->blood_type_needed }}</td>
                                                                    <td class="px-3 py-2 text-sm text-gray-900">{{ $request->quantity_requested }}</td>
                                                                    <td class="px-3 py-2 text-sm">
                                                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold @if($donor->availability_status === 'available') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">{{ ucfirst($donor->availability_status) }}</span>
                                                                    </td>
                                                                    <td class="px-3 py-2 text-sm text-gray-900">{{ $donor->contact_number }}</td>
                                                                    <td class="px-3 py-2 text-sm text-gray-900">{{ optional($donor->last_donation_date)->format('M d, Y') ?? 'N/A' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">No blood requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $bloodRequests->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
