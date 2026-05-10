<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Donation Details</h2>
            <a href="{{ route('donations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                <i class="fas fa-arrow-left mr-2"></i>Back to Donations
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-sm text-gray-500">Donation ID</p>
                        <p class="mt-1 text-lg font-medium text-gray-900">{{ $donation->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Donation Date</p>
                        <p class="mt-1 text-lg font-medium text-gray-900">{{ $donation->donation_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Blood Type</p>
                        <p class="mt-1 text-lg font-medium text-gray-900">{{ $donation->blood_type }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Quantity</p>
                        <p class="mt-1 text-lg font-medium text-gray-900">{{ $donation->quantity_donated }} ml</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Screening Result</p>
                        <p class="mt-1 text-lg font-medium text-gray-900">{{ $donation->screening_result ?? 'Pending' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="mt-1 text-lg font-medium text-gray-900">{{ ucfirst($donation->status ?? 'completed') }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-sm text-gray-500">Donor</p>
                        <p class="mt-1 text-lg font-medium text-gray-900">{{ $donation->donor->full_name ?? 'N/A' }}</p>
                    </div>
                    @if($donation->notes)
                        <div class="sm:col-span-2">
                            <p class="text-sm text-gray-500">Notes</p>
                            <p class="mt-1 text-gray-900">{{ $donation->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
