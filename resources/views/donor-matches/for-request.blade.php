<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Donor Matches for Request {{ $bloodRequest->request_id }}</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Match ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matched At</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responded At</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bloodRequest->donorMatches as $match)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $match->id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $match->donor->full_name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $match->donor->blood_type ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ ucfirst($match->status) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ optional($match->matched_at)->format('M d, Y H:i') ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ optional($match->responded_at)->format('M d, Y H:i') ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $match->response_notes ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        @if($match->isPending() && auth()->user()->isDonor() && $match->donor->user_id == auth()->id())
                                            <form method="POST" action="{{ route('donor-matches.accept', $match->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">Accept</button>
                                            </form>
                                            <form method="POST" action="{{ route('donor-matches.decline', $match->id) }}" class="inline ml-2">
                                                @csrf
                                                <input type="text" name="response_notes" placeholder="Notes (optional)" class="border rounded px-1 py-0.5 text-xs" />
                                                <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">Decline</button>
                                            </form>
                                        @else
                                            <a href="{{ route('donor-matches.show', $match->id) }}" class="text-blue-600 hover:underline">View</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">No matches found for this request.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    <a href="{{ route('donor-matches.index') }}" class="text-gray-600 hover:underline">← Back to all matches</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
