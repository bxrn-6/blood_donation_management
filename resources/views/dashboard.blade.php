<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->isAdmin())
                <!-- Admin Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                        <i class="fas fa-users text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Donors</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ App\Models\Donor::count() }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                        <i class="fas fa-tint text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Blood Units</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ App\Models\BloodInventory::sum('quantity') }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                        <i class="fas fa-hand-holding-medical text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Pending Requests</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ App\Models\BloodRequest::where('status', 'Pending')->count() }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                        <i class="fas fa-hand-holding-heart text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Donations</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ App\Models\Donation::count() }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Donations</h3>
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    @php
                                        $recentDonations = App\Models\Donation::with('donor.user')->orderBy('donation_date', 'desc')->take(5)->get();
                                    @endphp
                                    @forelse($recentDonations as $donation)
                                        <li class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                        <i class="fas fa-tint text-white text-xs"></i>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">{{ $donation->donor->full_name }}</p>
                                                        <p class="text-sm text-gray-900">{{ $donation->quantity_donated }} units of {{ $donation->blood_type }}</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        {{ $donation->donation_date->format('M d, Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="text-sm text-gray-500">No recent donations</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Blood Requests</h3>
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    @php
                                        $recentRequests = App\Models\BloodRequest::with('hospital')->orderBy('request_date', 'desc')->take(5)->get();
                                    @endphp
                                    @forelse($recentRequests as $request)
                                        <li class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                        <i class="fas fa-hand-holding-medical text-white text-xs"></i>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">{{ $request->hospital_name }}</p>
                                                        <p class="text-sm text-gray-900">{{ $request->quantity_requested }} units of {{ $request->blood_type_needed }}</p>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                            @if($request->status == 'Pending') bg-yellow-100 text-yellow-800
                                                            @elseif($request->status == 'Approved') bg-blue-100 text-blue-800
                                                            @elseif($request->status == 'Fulfilled') bg-green-100 text-green-800
                                                            @else bg-red-100 text-red-800 @endif">
                                                            {{ $request->status }}
                                                        </span>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        {{ $request->request_date->format('M d, Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="text-sm text-gray-500">No recent requests</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif(auth()->user()->isDonor())
                <!-- Donor Dashboard -->
                @if(auth()->user()->donor)
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Your Donor Profile</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-500">Full Name</p>
                                    <p class="text-lg font-medium text-gray-900">{{ auth()->user()->donor->full_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Blood Type</p>
                                    <p class="text-lg font-medium text-gray-900">{{ auth()->user()->donor->blood_type }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Availability Status</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if(auth()->user()->donor->availability_status == 'available') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ auth()->user()->donor->availability_status }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Last Donation</p>
                                    <p class="text-lg font-medium text-gray-900">
                                        {{ auth()->user()->donor->last_donation_date ? auth()->user()->donor->last_donation_date->format('M d, Y') : 'Never' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <a href="{{ route('donors.show', auth()->user()->donor->id) }}" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                                    View Full Profile
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Pending Donation Requests</h3>
                            @php
                                $pendingMatches = App\Models\DonorMatch::with('bloodRequest.hospital')
                                    ->where('donor_id', auth()->user()->donor->id)
                                    ->where('status', 'pending')
                                    ->get();
                            @endphp
                            @if($pendingMatches->count() > 0)
                                <div class="space-y-4">
                                    @foreach($pendingMatches as $match)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $match->bloodRequest->hospital_name }}</p>
                                                    <p class="text-sm text-gray-500">{{ $match->bloodRequest->quantity_requested }} units of {{ $match->bloodRequest->blood_type_needed }}</p>
                                                    <p class="text-sm text-gray-500">Urgency: {{ $match->bloodRequest->urgency_level }}</p>
                                                </div>
                                                <div class="space-x-2">
                                                    <form method="POST" action="{{ route('donor-matches.accept', $match->id) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                                            Accept
                                                        </button>
                                                    </form>
                                                    <button onclick="showDeclineForm({{ $match->id }})" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                                                        Decline
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No pending donation requests at this time.</p>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6 text-center">
                            <i class="fas fa-user-plus text-6xl text-red-600 mb-4"></i>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2">Create Your Donor Profile</h3>
                            <p class="text-gray-500 mb-4">Register as a blood donor to start saving lives.</p>
                            <a href="{{ route('donors.create') }}" class="bg-red-600 text-white px-6 py-3 rounded-md hover:bg-red-700">
                                Create Profile
                            </a>
                        </div>
                    </div>
                @endif

            @elseif(auth()->user()->isHospital())
                <!-- Hospital Dashboard -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Welcome, {{ auth()->user()->name }}</h3>
                        <p class="text-gray-500 mb-6">Manage blood requests and track their status.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600">
                                    {{ App\Models\BloodRequest::where('hospital_id', auth()->id())->count() }}
                                </div>
                                <div class="text-sm text-gray-500">Total Requests</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-yellow-600">
                                    {{ App\Models\BloodRequest::where('hospital_id', auth()->id())->where('status', 'Pending')->count() }}
                                </div>
                                <div class="text-sm text-gray-500">Pending</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-green-600">
                                    {{ App\Models\BloodRequest::where('hospital_id', auth()->id())->where('status', 'Fulfilled')->count() }}
                                </div>
                                <div class="text-sm text-gray-500">Fulfilled</div>
                            </div>
                        </div>
                        
                        <a href="{{ route('blood-requests.create') }}" class="bg-red-600 text-white px-6 py-3 rounded-md hover:bg-red-700">
                            <i class="fas fa-plus-circle mr-2"></i>New Blood Request
                        </a>
                    </div>
                </div>

                <div class="mt-8 bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Your Recent Requests</h3>
                        @php
                            $recentRequests = App\Models\BloodRequest::where('hospital_id', auth()->id())
                                ->orderBy('request_date', 'desc')
                                ->take(5)
                                ->get();
                        @endphp
                        @if($recentRequests->count() > 0)
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($recentRequests as $request)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $request->request_id }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->blood_type_needed }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->quantity_requested }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($request->status == 'Pending') bg-yellow-100 text-yellow-800
                                                        @elseif($request->status == 'Approved') bg-blue-100 text-blue-800
                                                        @elseif($request->status == 'Fulfilled') bg-green-100 text-green-800
                                                        @else bg-red-100 text-red-800 @endif">
                                                        {{ $request->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">No blood requests found.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
