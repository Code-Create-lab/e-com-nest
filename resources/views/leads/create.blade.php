@extends('layouts.app')

@section('title', 'Create Lead')
@section('page-title', 'Create Lead')
@section('page-eyebrow', 'Lead Management')

@section('content')
    <x-admin.page-header title="Create lead" description="Capture a new lead with source, contact details, and qualification status." />

    <section class="mt-6 rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
        <form action="{{ route('leads.store') }}" method="POST">
            @include('leads.partials.form', ['submitLabel' => 'Create lead'])
        </form>
    </section>
@endsection
