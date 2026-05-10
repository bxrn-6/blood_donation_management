<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Record a Blood Donation</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('donations.store') }}">
                    @csrf

                    @if(auth()->user()->isAdmin())
                        <div class="mb-4">
                            <label for="donor_id" class="block text-sm font-medium text-gray-700">Donor</label>
                            <select id="donor_id" name="donor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                <option value="">Select a donor</option>
                                @foreach($donors as $donor)
                                    <option value="{{ $donor->id }}" @selected(old('donor_id') == $donor->id)>
                                        {{ $donor->full_name }} ({{ $donor->blood_type }})
                                    </option>
                                @endforeach
                            </select>
                            @error('donor_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    @else
                        <input type="hidden" name="donor_id" value="{{ auth()->user()->donor->id }}">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Donor</label>
                            <p class="mt-1 text-gray-700">{{ auth()->user()->donor->full_name }} ({{ auth()->user()->donor->blood_type }})</p>
                        </div>
                    @endif

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="blood_type" class="block text-sm font-medium text-gray-700">Blood Type</label>
                            <select id="blood_type" name="blood_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                <option value="">Select type</option>
                                @foreach($bloodTypes as $type)
                                    <option value="{{ $type }}" @selected(old('blood_type') == $type)>{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('blood_type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="quantity_donated" class="block text-sm font-medium text-gray-700">Units Donated</label>
                            <input id="quantity_donated" name="quantity_donated" type="number" min="1" value="{{ old('quantity_donated') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Enter units donated">
                            @error('quantity_donated') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2 mt-4">
                        <div>
                            <label for="donation_date" class="block text-sm font-medium text-gray-700">Donation Date</label>
                            <input id="donation_date" name="donation_date" type="date" value="{{ old('donation_date', now()->toDateString()) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                            @error('donation_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="screening_result" class="block text-sm font-medium text-gray-700">Screening Result</label>
                            <input id="screening_result" name="screening_result" type="text" value="{{ old('screening_result') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="e.g. Passed, Pending">
                            @error('screening_result') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">{{ old('notes') }}</textarea>
                        @error('notes') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-6 flex items-center justify-end space-x-3">
                        <a href="{{ route('donations.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Cancel</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            <i class="fas fa-save mr-2"></i>Save Donation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
