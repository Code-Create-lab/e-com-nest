@extends('layouts.app')

@section('title', 'Create Project')
@section('page-title', 'Create Project')
@section('page-eyebrow', 'Project Management')

@section('content')
    <x-admin.page-header title="Create project" description="Create a customer-linked project with status, dates, and progress tracking.">
        <a href="{{ route('customers.create') }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
            Add customer first
        </a>
    </x-admin.page-header>

    <section class="mt-6 rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
        <form action="{{ route('projects.store') }}" method="POST">
            @include('projects.partials.form', ['submitLabel' => 'Create project'])
        </form>
    </section>
@endsection
