<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Blood Requests</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Blood Requests</h1>
                    <p class="text-sm text-gray-500">Manage your hospital blood requests.</p>
                </div>
                <a href="{{ route('blood-requests.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">New Request</a>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hospital</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgency</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
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
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ ucfirst($request->urgency_level) }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold @if($request->status === 'Pending') bg-yellow-100 text-yellow-800 @elseif($request->status === 'Approved') bg-blue-100 text-blue-800 @elseif($request->status === 'Fulfilled') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">{{ $request->status }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $request->request_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <a href="{{ route('blood-requests.show', $request->id) }}" class="text-red-600 hover:text-red-900">View</a>
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

                <div class="p-4">{{ $bloodRequests->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
