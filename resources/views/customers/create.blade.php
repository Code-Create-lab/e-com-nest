@extends('layouts.app')

@section('title', 'Create Customer')
@section('page-title', 'Create Customer')
@section('page-eyebrow', 'Customer Management')

@section('content')
    <x-admin.page-header title="Create customer" description="Add a new customer record with company and contact details ready for projects and invoices." />

    <section class="mt-6 rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
        <form action="{{ route('customers.store') }}" method="POST">
            @include('customers.partials.form', ['submitLabel' => 'Create customer'])
        </form>
    </section>
@endsection
