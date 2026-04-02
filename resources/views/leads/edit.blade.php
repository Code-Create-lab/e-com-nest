@extends('layouts.app')

@section('title', 'Edit Lead')
@section('page-title', 'Edit Lead')
@section('page-eyebrow', 'Lead Management')

@section('content')
    <x-admin.page-header title="Edit lead" description="Update lead qualification details, contact info, and source attribution.">
        <a href="{{ route('leads.show', $lead) }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
            View lead
        </a>
    </x-admin.page-header>

    <section class="mt-6 rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
        <form action="{{ route('leads.update', $lead) }}" method="POST">
            @method('PUT')
            @include('leads.partials.form', ['submitLabel' => 'Save changes'])
        </form>
    </section>
@endsection
