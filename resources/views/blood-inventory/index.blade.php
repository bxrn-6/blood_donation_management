<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Blood Inventory</h2>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('blood-inventory.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    <i class="fas fa-plus mr-2"></i>Add Blood Unit
                </a>
            @endif
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
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donation Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiration Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                @if(auth()->user()->isAdmin())
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($inventory as $item)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ $item->blood_type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->donation_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $item->expiration_date->format('M d, Y') }}
                                        @if($item->expiration_date->isPast())
                                            <span class="ml-2 text-xs text-red-600 font-medium">(Expired)</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        @if($item->status === 'Available')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $item->status }}
                                            </span>
                                        @elseif($item->status === 'Low')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ $item->status }}
                                            </span>
                                        @elseif($item->status === 'Expired')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ $item->status }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $item->status }}
                                            </span>
                                        @endif
                                    </td>
                                    @if(auth()->user()->isAdmin())
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            <a href="{{ route('blood-inventory.show', $item->id) }}" class="text-red-600 hover:text-red-800 mr-3">View</a>
                                            <a href="{{ route('blood-inventory.edit', $item->id) }}" class="text-red-600 hover:text-red-800 mr-3">Edit</a>
                                            <form action="{{ route('blood-inventory.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this blood unit?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}" class="px-4 py-6 text-center text-sm text-gray-500">No blood inventory found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if(auth()->user()->isAdmin())
                <div class="mt-4 flex justify-end">
                    <form action="{{ route('blood-inventory.update-expired') }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                            <i class="fas fa-sync-alt mr-2"></i>Update Expired Status
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
