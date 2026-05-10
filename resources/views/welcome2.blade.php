<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Blood Donation Management System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900">
        <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-red-50 via-white to-red-100 px-4 py-8">
            <div class="w-full max-w-6xl overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-2xl shadow-slate-200/70 backdrop-blur-xl">
                <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
                    <section class="p-10 lg:p-14">
                        <div class="inline-flex items-center gap-3 rounded-full bg-red-50 px-4 py-2 text-red-700 shadow-sm shadow-red-100">
                            <i class="fas fa-tint text-xl"></i>
                            <span class="text-sm font-semibold uppercase tracking-[0.28em]">Blood Bank</span>
                        </div>

                        <div class="mt-10 max-w-xl">
                            <h1 class="text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl">Secure access for donors, hospitals, and admin users</h1>
                            <p class="mt-6 text-base leading-8 text-slate-600">Log in to manage donations, blood inventory, request fulfillment, and donor matching. New users can register to join the system and help save lives.</p>
                        </div>

                        <div class="mt-10 flex flex-col gap-4 sm:flex-row sm:items-center">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-full bg-red-600 px-7 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700">Go to Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full bg-red-600 px-7 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700">Login</a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-7 py-3 text-sm font-semibold text-slate-900 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">Register</a>
                                    @endif
                                @endauth
                            @endif
                        </div>

                        <div class="mt-12 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                                <p class="text-sm font-semibold text-slate-900">For hospitals</p>
                                <p class="mt-3 text-sm leading-6 text-slate-600">Submit blood requests, review matches, and coordinate fulfillment from one secure portal.</p>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                                <p class="text-sm font-semibold text-slate-900">For donors</p>
                                <p class="mt-3 text-sm leading-6 text-slate-600">Register your donor profile, update availability, and track your donation history.</p>
                            </div>
                        </div>
                    </section>

                    <aside class="relative hidden overflow-hidden rounded-bl-[2rem] rounded-br-[2rem] bg-red-600 p-10 text-white lg:block">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(255,255,255,0.22),_transparent_32%)]"></div>
                        <div class="relative space-y-8">
                            <p class="text-sm uppercase tracking-[0.3em] text-red-100/90">Trusted access</p>
                            <h2 class="text-3xl font-semibold tracking-tight">A modern blood donation management portal</h2>
                            <p class="max-w-md text-base leading-7 text-white/80">Sign in or register to manage donation workflows, keep inventory up to date, and support urgent transfusion needs.</p>

                            <div class="rounded-3xl border border-white/15 bg-white/10 p-6 backdrop-blur-sm">
                                <p class="text-sm uppercase tracking-[0.24em] text-white/80">Ready to help</p>
                                <p class="mt-3 text-xl font-semibold text-white">Make every unit count.</p>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </body>
</html>
