<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Blood Request Details</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ $bloodRequest->request_id }}</h3>
                        <p class="text-sm text-gray-500">{{ $bloodRequest->hospital_name }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('blood-requests.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Back</a>
                        @if($bloodRequest->status === 'Pending' && (auth()->user()->isAdmin() || auth()->id() === $bloodRequest->hospital_id))
                            <a href="{{ route('blood-requests.edit', $bloodRequest->id) }}" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700">Edit</a>
                        @endif
                    </div>
                </div>

                <div class="mt-6 grid gap-6 md:grid-cols-2">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700">Request Information</h4>
                        <dl class="mt-4 space-y-3 text-sm text-gray-700">
                            <div>
                                <dt class="font-medium">Blood Type Needed</dt>
                                <dd>{{ $bloodRequest->blood_type_needed }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium">Units Requested</dt>
                                <dd>{{ $bloodRequest->quantity_requested }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium">Urgency Level</dt>
                                <dd>{{ ucfirst($bloodRequest->urgency_level) }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium">Request Date</dt>
                                <dd>{{ $bloodRequest->request_date->format('M d, Y') }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium">Status</dt>
                                <dd>{{ $bloodRequest->status }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium">Notes</dt>
                                <dd>{{ $bloodRequest->notes ?? 'No notes provided.' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700">Matching Donors</h4>
                        @if($bloodRequest->donorMatches->isEmpty())
                            <p class="mt-4 text-sm text-gray-500">No matching donors available yet.</p>
                        @else
                            <div class="mt-4 space-y-4">
                                @foreach($bloodRequest->donorMatches as $match)
                                    <div class="rounded-lg border border-gray-200 p-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $match->donor->full_name }}</p>
                                                <p class="text-sm text-gray-500">Blood Type: {{ $match->donor->blood_type }}</p>
                                            </div>
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">{{ ucfirst($match->donor->availability_status) }}</span>
                                        </div>
                                        <div class="mt-3 text-sm text-gray-600">
                                            <p>Contact: {{ $match->donor->contact_number }}</p>
                                            <p>Last Donation: {{ optional($match->donor->last_donation_date)->format('M d, Y') ?? 'N/A' }}</p>
                                            <p>Request Blood Type: {{ $bloodRequest->blood_type_needed }}</p>
                                            <p>Units Requested: {{ $bloodRequest->quantity_requested }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                @if(auth()->user()->isAdmin() && $bloodRequest->status === 'Pending')
                    <div class="mt-6 flex flex-wrap gap-3">
                        <form method="POST" action="{{ route('blood-requests.approve', $bloodRequest->id) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">Approve</button>
                        </form>
                        <form method="POST" action="{{ route('blood-requests.reject', $bloodRequest->id) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700">Reject</button>
                        </form>
                        <form method="POST" action="{{ route('blood-requests.fulfill', $bloodRequest->id) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">Fulfill</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
