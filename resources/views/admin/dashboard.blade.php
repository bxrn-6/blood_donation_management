<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <h3 class="text-sm font-medium text-gray-500">Total Donors</h3>
                    <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $totalDonors }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <h3 class="text-sm font-medium text-gray-500">Available Blood Units</h3>
                    <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $availableBloodUnits }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <h3 class="text-sm font-medium text-gray-500">Pending Requests</h3>
                    <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $pendingRequests }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <h3 class="text-sm font-medium text-gray-500">Total Donations</h3>
                    <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $totalDonations }}</p>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Donors</h3>
                        <p class="text-sm text-gray-500">Search, filter, edit, and delete donor records.</p>
                    </div>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                        <input type="text" name="donor_search" value="{{ request('donor_search') }}" placeholder="Search donors..." class="border rounded-md px-3 py-2 text-sm w-full md:w-80" />
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">Search</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Donation</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($donors as $donor)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $donor->id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $donor->full_name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $donor->blood_type }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $donor->contact_number }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 truncate max-w-xs">{{ $donor->address }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold @if($donor->availability_status === 'available') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">{{ ucfirst($donor->availability_status) }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ optional($donor->last_donation_date)->format('M d, Y') ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('donors.edit', $donor->id) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                            <form method="POST" action="{{ route('donors.destroy', $donor->id) }}" onsubmit="return confirm('Delete this donor?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">No donors found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $donors->links() }}</div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Blood Inventory</h3>
                        <p class="text-sm text-gray-500">Track available units, expiration dates, and storage status.</p>
                    </div>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                        <input type="text" name="inventory_search" value="{{ request('inventory_search') }}" placeholder="Search inventory..." class="border rounded-md px-3 py-2 text-sm w-full md:w-80" />
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">Search</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Collected</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiration</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bloodInventory as $item)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->blood_type }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->donation_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->expiration_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold @if($item->status === 'Available') bg-green-100 text-green-800 @elseif($item->status === 'Low') bg-yellow-100 text-yellow-800 @else bg-red-100 text-red-800 @endif">{{ $item->status }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <a href="{{ route('blood-inventory.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">No inventory records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $bloodInventory->links() }}</div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Blood Requests</h3>
                        <p class="text-sm text-gray-500">Review and approve or reject hospital requests.</p>
                    </div>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                        <input type="text" name="request_search" value="{{ request('request_search') }}" placeholder="Search requests..." class="border rounded-md px-3 py-2 text-sm w-full md:w-80" />
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">Search</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requester</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgency</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
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
                                    <td class="px-4 py-3 text-sm space-x-2">
                                        <a href="{{ route('blood-requests.show', $request->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        @if($request->status === 'Pending')
                                            <form method="POST" action="{{ route('blood-requests.approve', $request->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                                            </form>
                                            <form method="POST" action="{{ route('blood-requests.reject', $request->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                            </form>
                                        @endif
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

            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Donations</h3>
                        <p class="text-sm text-gray-500">Monitor donation history and blood collection activity.</p>
                    </div>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                        <input type="text" name="donation_search" value="{{ request('donation_search') }}" placeholder="Search donations..." class="border rounded-md px-3 py-2 text-sm w-full md:w-80" />
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">Search</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donation ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Screening</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($donations as $donation)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $donation->id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ optional($donation->donor)->full_name ?? 'Unknown' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $donation->blood_type }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $donation->donation_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $donation->quantity_donated }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">N/A</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold @if($donation->status === 'completed') bg-green-100 text-green-800 @else bg-yellow-100 text-yellow-800 @endif">{{ ucfirst($donation->status) }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <a href="{{ route('donations.show', $donation->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">No donations found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $donations->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
