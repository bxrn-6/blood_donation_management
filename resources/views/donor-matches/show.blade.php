<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Blood Match Details</h2>
            <a href="{{ route('donor-matches.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                Back to Matches
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Donor Information</h3>
                        <dl class="mt-4 space-y-3 text-sm text-gray-700">
                            <div>
                                <dt class="font-medium">Donor Name</dt>
                                <dd>{{ $donorMatch->donor->full_name }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium">Donor Blood Type</dt>
                                <dd>{{ $donorMatch->donor->blood_type }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium">Units Available</dt>
                                <dd>{{ $donorMatch->bloodRequest->quantity_requested }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium">Availability Status</dt>
                                <dd>{{ ucfirst($donorMatch->donor->availability_status) }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Request Information</h3>
                        <dl class="mt-4 space-y-3 text-sm text-gray-700">
                            <div>
                                <dt class="font-medium">Requesting Hospital</dt>
                                <dd>{{ $donorMatch->bloodRequest->hospital_name }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium">Blood Type Needed</dt>
                                <dd>{{ $donorMatch->bloodRequest->blood_type_needed }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium">Units Requested</dt>
                                <dd>{{ $donorMatch->bloodRequest->quantity_requested }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium">Match Status</dt>
                                <dd>{{ ucfirst($donorMatch->status) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                @if(auth()->user()->isDonor() && $donorMatch->isPending())
                    <div class="mt-6 flex flex-wrap gap-3">
                        <form method="POST" action="{{ route('donor-matches.accept', $donorMatch->id) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Accept</button>
                        </form>
                        <form method="POST" action="{{ route('donor-matches.decline', $donorMatch->id) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Decline</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
