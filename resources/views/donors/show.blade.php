<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Donor Profile</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ $donor->full_name }}</h3>
                        <p class="text-sm text-gray-500">Donor ID: {{ $donor->id }}</p>
                    </div>
                    <div class="space-x-2">
                        @if(auth()->user()->isAdmin() || auth()->id() === $donor->user_id)
                            <a href="{{ route('donors.edit', $donor->id) }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm">Edit Profile</a>
                        @endif
                        <a href="{{ route('donor.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Back to Dashboard</a>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Full Name</p>
                            <p class="text-lg font-medium text-gray-900">{{ $donor->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Blood Type</p>
                            <p class="text-lg font-medium text-gray-900">{{ $donor->blood_type }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Contact</p>
                            <p class="text-lg font-medium text-gray-900">{{ $donor->contact_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Address</p>
                            <p class="text-lg font-medium text-gray-900">{{ $donor->address }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Eligibility</p>
                            <p class="text-lg font-medium text-gray-900">{{ $donor->canDonate() ? 'Eligible' : 'Not eligible' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Availability Status</p>
                            <p class="text-lg font-medium text-gray-900">{{ ucfirst($donor->availability_status) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Last Donation Date</p>
                            <p class="text-lg font-medium text-gray-900">{{ $donor->last_donation_date ? $donor->last_donation_date->format('M d, Y') : 'Never' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-lg font-medium text-gray-900">{{ $donor->user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
