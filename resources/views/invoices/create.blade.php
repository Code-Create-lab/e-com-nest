@extends('layouts.app')

@section('title', 'Create Invoice')
@section('page-title', 'Create Invoice')
@section('page-eyebrow', 'Invoice Management')

@section('content')
    <x-admin.page-header title="Create invoice" description="Generate a structured invoice with calculated totals and optional project linkage." />

    <section class="mt-6 rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
        <form action="{{ route('invoices.store') }}" method="POST">
            @include('invoices.partials.form', ['submitLabel' => 'Create invoice'])
        </form>
    </section>
@endsection
