<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Donors Management
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Blood Donors</h1>
                    @if(auth()->user()->isDonor() && !auth()->user()->donor)
                    </a>
                @endif
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="min-w-full divide-y divide-gray-200">
                    <div class="bg-gray-50 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>Name</div>
                            <div>Blood Type</div>
                            <div>Contact</div>
                            <div>Availability</div>
                            <div>Actions</div>
                        </div>
                    </div>
                    <div class="bg-white divide-y divide-gray-200">
                        @forelse($donors as $donor)
                            <div class="px-6 py-4 whitespace-nowrap">
                                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $donor->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $donor->user->email }}</div>
                                    </div>
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ $donor->blood_type }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-900">{{ $donor->contact_number }}</div>
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($donor->availability_status == 'available') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ $donor->availability_status }}
                                        </span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('donors.show', $donor->id) }}" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->isAdmin() || auth()->id() == $donor->user_id)
                                            <a href="{{ route('donors.edit', $donor->id) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if(auth()->user()->isAdmin())
                                            <form method="POST" action="{{ route('donors.destroy', $donor->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this donor?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-4 text-center text-gray-500">
                                No donors found.
                            </div>
                        @endforelse
                    </div>
                </div>

                @if($donors->hasPages())
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <a href="{{ $donors->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                            </a>
                            <a href="{{ $donors->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Next
                            </a>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{ $donors->firstItem() }}</span>
                                    to
                                    <span class="font-medium">{{ $donors->lastItem() }}</span>
                                    of
                                    <span class="font-medium">{{ $donors->total() }}</span>
                                    results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <!-- Previous -->
                                    @if($donors->onFirstPage())
                                        <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400">
                                            Previous
                                        </span>
                                    @else
                                        <a href="{{ $donors->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            Previous
                                        </a>
                                    @endif

                                    <!-- Page Numbers -->
                                    @foreach($elements = $donors->links()->elements as $element)
                                        @if(is_string($element))
                                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                                {{ $element }}
                                            </span>
                                        @endif

                                        @if(is_array($element))
                                            @foreach($element as $page => $url)
                                                @if($page == $donors->currentPage())
                                                    <span aria-current="page" class="relative inline-flex items-center px-4 py-2 border border-red-500 bg-red-50 text-sm font-medium text-red-600">
                                                        {{ $page }}
                                                    </span>
                                                @else
                                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                        {{ $page }}
                                                    </a>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach

                                    <!-- Next -->
                                    @if($donors->hasMorePages())
                                        <a href="{{ $donors->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            Next
                                        </a>
                                    @else
                                        <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400">
                                            Next
                                        </span>
                                    @endif
                                </nav>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
