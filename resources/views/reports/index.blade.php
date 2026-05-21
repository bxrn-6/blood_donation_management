<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-chart-bar mr-2 text-red-600"></i>Reports
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <p class="text-gray-600">Select a report type below to view detailed analytics and export data.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Dashboard Report --}}
                <a href="{{ route('reports.dashboard') }}" class="block bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow border-l-4 border-red-600">
                    <div class="flex items-center mb-4">
                        <div class="bg-red-100 rounded-full p-3 mr-4">
                            <i class="fas fa-tachometer-alt text-red-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Dashboard Overview</h3>
                    </div>
                    <p class="text-gray-600 text-sm">Comprehensive summary of key metrics, recent activity, and blood type distribution.</p>
                    <div class="mt-4 text-red-600 text-sm font-medium">View Report &rarr;</div>
                </a>

                {{-- Donations Report --}}
                <a href="{{ route('reports.donations') }}" class="block bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow border-l-4 border-pink-500">
                    <div class="flex items-center mb-4">
                        <div class="bg-pink-100 rounded-full p-3 mr-4">
                            <i class="fas fa-hand-holding-heart text-pink-500 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Donations Report</h3>
                    </div>
                    <p class="text-gray-600 text-sm">Filter and analyze donation records by date range and blood type. Export to Excel.</p>
                    <div class="mt-4 text-pink-500 text-sm font-medium">View Report &rarr;</div>
                </a>

                {{-- Inventory Report --}}
                <a href="{{ route('reports.inventory') }}" class="block bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow border-l-4 border-blue-500">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 rounded-full p-3 mr-4">
                            <i class="fas fa-flask text-blue-500 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Inventory Report</h3>
                    </div>
                    <p class="text-gray-600 text-sm">View blood inventory levels by type and status. Track available, low, and expired units.</p>
                    <div class="mt-4 text-blue-500 text-sm font-medium">View Report &rarr;</div>
                </a>

                {{-- Requests Report --}}
                <a href="{{ route('reports.requests') }}" class="block bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow border-l-4 border-yellow-500">
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-100 rounded-full p-3 mr-4">
                            <i class="fas fa-hand-holding-medical text-yellow-500 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Blood Requests Report</h3>
                    </div>
                    <p class="text-gray-600 text-sm">Analyze blood request records by status and urgency. Track fulfillment rates.</p>
                    <div class="mt-4 text-yellow-500 text-sm font-medium">View Report &rarr;</div>
                </a>

                {{-- Donor Matches Report --}}
                <a href="{{ route('reports.donor-matches') }}" class="block bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow border-l-4 border-green-500">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 rounded-full p-3 mr-4">
                            <i class="fas fa-link text-green-500 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Donor Matches Report</h3>
                    </div>
                    <p class="text-gray-600 text-sm">Review donor-to-request match records, acceptance rates, and match outcomes.</p>
                    <div class="mt-4 text-green-500 text-sm font-medium">View Report &rarr;</div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
