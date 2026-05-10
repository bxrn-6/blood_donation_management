<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Blood Request</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('blood-requests.update', $bloodRequest->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-6">
                        <div>
                            <label for="blood_type_needed" class="block text-sm font-medium text-gray-700">Blood Type Needed</label>
                            <select id="blood_type_needed" name="blood_type_needed" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                <option value="">Select blood type</option>
                                @foreach($bloodTypes as $type)
                                    <option value="{{ $type }}" @selected(old('blood_type_needed', $bloodRequest->blood_type_needed) === $type)>{{ $type }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('blood_type_needed')" class="mt-2" />
                        </div>

                        <div>
                            <label for="quantity_requested" class="block text-sm font-medium text-gray-700">Units Requested</label>
                            <input id="quantity_requested" name="quantity_requested" type="number" min="1" value="{{ old('quantity_requested', $bloodRequest->quantity_requested) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" />
                            <x-input-error :messages="$errors->get('quantity_requested')" class="mt-2" />
                        </div>

                        <div>
                            <label for="urgency_level" class="block text-sm font-medium text-gray-700">Urgency Level</label>
                            <select id="urgency_level" name="urgency_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                <option value="">Select urgency</option>
                                @foreach($urgencyLevels as $level)
                                    <option value="{{ $level }}" @selected(old('urgency_level', $bloodRequest->urgency_level) === $level)>{{ ucfirst($level) }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('urgency_level')" class="mt-2" />
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">{{ old('notes', $bloodRequest->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('blood-requests.show', $bloodRequest->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700">Update Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
