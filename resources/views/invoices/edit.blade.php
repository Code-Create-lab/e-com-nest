@extends('layouts.app')

@section('title', 'Edit Invoice')
@section('page-title', 'Edit Invoice')
@section('page-eyebrow', 'Invoice Management')

@section('content')
    <x-admin.page-header title="Edit invoice" :description="'Invoice number: '.$invoice->invoice_number">
        <a href="{{ route('invoices.show', $invoice) }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
            View invoice
        </a>
    </x-admin.page-header>

    <section class="mt-6 rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
        <form action="{{ route('invoices.update', $invoice) }}" method="POST">
            @method('PUT')
            @include('invoices.partials.form', ['submitLabel' => 'Save changes'])
        </form>
    </section>
@endsection
