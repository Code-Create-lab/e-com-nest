@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
    <div class="auth-card-3d p-8" data-tilt data-tilt-max="5">
        <img
            src="{{ asset('logo.jpeg') }}"
            alt="{{ config('app.name', 'Admin Panel') }}"
            class="mb-6 h-20 w-auto object-contain"
        >
        <p class="page-eyebrow-chip">Secure Access</p>
        <h1 class="mt-4 text-3xl font-semibold tracking-tight text-slate-950">Admin login</h1>
        <p class="mt-3 text-sm text-slate-500">Use the seeded administrator account to access the dashboard and manage the panel.</p>

        <form action="{{ route('login.store') }}" method="POST" class="mt-8 space-y-5">
            @csrf

            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="input-3d w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none">
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                <input id="password" name="password" type="password" value="password" required class="input-3d w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none">
            </div>

            <label class="flex items-center gap-3 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500" {{ old('remember') ? 'checked' : '' }}>
                Remember this device
            </label>

            <button type="submit" data-magnetic data-magnetic-strength="0.22" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
                Sign in
            </button>
        </form>

        <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm text-slate-600 backdrop-blur">
            Demo credentials: <span class="font-medium text-slate-900">admin@example.com</span> / <span class="font-medium text-slate-900">password</span>
        </div>
    </div>
@endsection
