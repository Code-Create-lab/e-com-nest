@extends('layouts.app')

@section('title', 'Edit Project')
@section('page-title', 'Edit Project')
@section('page-eyebrow', 'Project Management')

@section('content')
    <x-admin.page-header title="Edit project" description="Keep project schedules and delivery progress accurate as work evolves.">
        <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
            View project
        </a>
    </x-admin.page-header>

    <section class="mt-6 rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
        <form action="{{ route('projects.update', $project) }}" method="POST">
            @method('PUT')
            @include('projects.partials.form', ['submitLabel' => 'Save changes'])
        </form>
    </section>
@endsection
