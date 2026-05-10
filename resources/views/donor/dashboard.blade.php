<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Donor Dashboard</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Donor Profile</h3>
                    <p class="mt-1 text-sm text-gray-500">Your personal donor information and eligibility status.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Donor ID</p>
                            <p class="text-lg font-medium text-gray-900">{{ $donor->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Full Name</p>
                            <p class="text-lg font-medium text-gray-900">{{ $donor->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Blood Type</p>
                            <p class="text-lg font-medium text-gray-900">{{ $donor->blood_type }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Contact Information</p>
                            <p class="text-lg font-medium text-gray-900">{{ $donor->contact_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Address</p>
                            <p class="text-lg font-medium text-gray-900">{{ $donor->address }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Gender</p>
                            <p class="text-lg font-medium text-gray-900">{{ $donor->gender ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Age</p>
                            <p class="text-lg font-medium text-gray-900">{{ $donor->age ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Eligibility Status</p>
                            <p class="text-lg font-medium text-gray-900">{{ $donor->canDonate() ? 'Eligible' : 'Not eligible' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Last Donation Date</p>
                            <p class="text-lg font-medium text-gray-900">{{ $donor->last_donation_date ? $donor->last_donation_date->format('M d, Y') : 'Never' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Next Eligible Donation</p>
                            <p class="text-lg font-medium text-gray-900">{{ $nextEligibleDate->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
                <div class="px-6 pb-6 flex flex-wrap gap-3">
                    <a href="{{ route('donors.edit', $donor->id) }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Update Profile</a>
                    <a href="{{ route('donations.create') }}" class="inline-flex items-center px-4 py-2 bg-white border border-red-600 text-red-600 rounded-md hover:bg-red-50">Donate Blood</a>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Last Donation Record</h3>
                    <p class="mt-1 text-sm text-gray-500">See your most recent donation details.</p>
                </div>
                <div class="p-6">
                    @if($lastDonation)
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <p class="text-sm text-gray-500">Donation Date</p>
                                <p class="text-lg font-medium text-gray-900">{{ $lastDonation->donation_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Blood Type</p>
                                <p class="text-lg font-medium text-gray-900">{{ $lastDonation->blood_type }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Quantity</p>
                                <p class="text-lg font-medium text-gray-900">{{ $lastDonation->quantity_donated }} ml</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Screening Result</p>
                                <p class="text-lg font-medium text-gray-900">{{ $lastDonation->screening_result ?? 'Pending' }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('donations.show', $lastDonation->id) }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                View Full Donation Record
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">You have not recorded a donation yet. Use the Donate Blood button to add your first donation.</p>
                    @endif
                </div>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Donation History</h3>
                    <p class="mt-1 text-sm text-gray-500">Track your previous donations over time.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donation ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Screening Result</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">No donation history available.</td>
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
