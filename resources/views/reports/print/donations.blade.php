<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Donations Report (Print)</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Donor</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Blood Type</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($donations as $donation)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $donation->id }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $donation->donation_date->format('Y-m-d') }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">
                                        {{ $donation->donor->full_name ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $donation->blood_type }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $donation->quantity_donated }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
