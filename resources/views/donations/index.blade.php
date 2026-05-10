<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Donation History</h2>
            <a href="{{ route('donations.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                <i class="fas fa-plus mr-2"></i>Record New Donation
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Screening Result</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                @if(auth()->user()->isAdmin())
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor</th>
                                @endif
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($donations as $donation)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $donation->id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $donation->donation_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $donation->blood_type }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $donation->quantity_donated }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $donation->screening_result ?? 'Pending' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ ucfirst($donation->status ?? 'completed') }}</td>
                                    @if(auth()->user()->isAdmin())
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $donation->donor->full_name ?? 'N/A' }}</td>
                                    @endif
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <a href="{{ route('donations.show', $donation->id) }}" class="text-red-600 hover:text-red-800">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->isAdmin() ? 8 : 6 }}" class="px-4 py-6 text-center text-sm text-gray-500">No donations found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">{{ $donations->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
