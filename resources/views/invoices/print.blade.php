<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 32px; font-family: Arial, sans-serif; color: #0f172a; background: #eef4fb; }
        .wrap { max-width: 980px; margin: 0 auto; }
        .actions { margin-bottom: 24px; }
        .print-btn { border: 0; border-radius: 999px; background: linear-gradient(135deg, #0f3d91, #ff9f1c); color: #fff; padding: 12px 20px; font-weight: 700; cursor: pointer; }
        .sheet { overflow: hidden; border-radius: 28px; background: #fff; box-shadow: 0 30px 80px rgba(15, 23, 42, 0.08); }
        .hero { padding: 28px 32px; color: #fff; background: linear-gradient(135deg, #0f3d91 0%, #1d5fd0 48%, #ff9f1c 100%); }
        .hero-grid { display: flex; justify-content: space-between; gap: 24px; align-items: flex-start; }
        .brand { display: flex; gap: 16px; align-items: center; }
        .logo-wrap { width: 88px; height: 88px; padding: 8px; border-radius: 24px; background: rgba(255,255,255,0.96); }
        .logo-wrap img { width: 100%; height: 100%; object-fit: contain; border-radius: 18px; }
        .eyebrow { margin: 0; font-size: 12px; letter-spacing: 0.28em; text-transform: uppercase; color: rgba(255,255,255,0.72); }
        .title { margin: 10px 0 0; font-size: 34px; font-weight: 700; }
        .subtitle { margin: 10px 0 0; max-width: 420px; font-size: 14px; line-height: 1.6; color: rgba(255,255,255,0.82); }
        .meta-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; min-width: 300px; }
        .meta-card { border: 1px solid rgba(255,255,255,0.16); border-radius: 18px; padding: 14px 16px; background: rgba(255,255,255,0.1); backdrop-filter: blur(8px); }
        .meta-card strong { display: block; margin-top: 8px; font-size: 16px; }
        .meta-card span { font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; color: rgba(255,255,255,0.72); }
        .content { padding: 28px 32px 32px; }
        .split { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
        .panel { border: 1px solid #e2e8f0; border-radius: 24px; padding: 20px; }
        .panel.orange { background: #fff7ed; border-color: #fed7aa; }
        .panel.slate { background: #f8fafc; }
        .panel-label { margin: 0; font-size: 11px; letter-spacing: 0.22em; text-transform: uppercase; color: #64748b; }
        .panel.orange .panel-label { color: #c2410c; }
        .panel h3 { margin: 12px 0 0; font-size: 22px; }
        .panel p { margin: 6px 0 0; font-size: 14px; color: #475569; line-height: 1.6; }
        .project { margin-top: 18px; border: 1px solid #dbeafe; border-radius: 20px; padding: 18px 20px; background: #f0f9ff; }
        .project strong { display: block; margin-top: 8px; font-size: 18px; color: #0f172a; }
        table { width: 100%; border-collapse: collapse; margin-top: 24px; overflow: hidden; border-radius: 24px; }
        thead { background: #0f172a; color: #fff; }
        th { padding: 16px 18px; font-size: 11px; letter-spacing: 0.2em; text-transform: uppercase; text-align: left; color: rgba(255,255,255,0.72); }
        td { padding: 18px; border-bottom: 1px solid #e2e8f0; font-size: 14px; vertical-align: top; }
        tbody tr:nth-child(even) td { background: #f8fafc; }
        .item-title { font-weight: 700; color: #0f172a; }
        .item-note { margin-top: 4px; font-size: 11px; letter-spacing: 0.14em; text-transform: uppercase; color: #94a3b8; }
        .totals { margin-top: 24px; display: flex; justify-content: flex-end; }
        .totals-card { width: 330px; border-radius: 24px; background: linear-gradient(180deg, #fff7ed, #ffffff); border: 1px solid #fed7aa; padding: 22px; }
        .total-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #e2e8f0; font-size: 14px; color: #475569; }
        .total-row strong { color: #0f172a; font-size: 18px; }
        .total-row:last-child { border-bottom: 0; padding-bottom: 0; }
        .grand-total span:last-child { font-size: 28px; font-weight: 700; color: #0f172a; }
        .notes { margin-top: 24px; border: 1px solid #e2e8f0; border-radius: 24px; padding: 20px; background: #f8fafc; }
        .notes p:first-child { margin: 0; font-size: 11px; letter-spacing: 0.22em; text-transform: uppercase; color: #64748b; }
        .notes p:last-child { margin: 12px 0 0; font-size: 14px; line-height: 1.7; color: #334155; }
        .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #64748b; }
        .footer strong { color: #0f172a; }
        @media (max-width: 860px) {
            body { padding: 16px; }
            .hero, .content { padding: 22px; }
            .hero-grid, .split { display: block; }
            .meta-grid { margin-top: 18px; min-width: 0; }
            .panel + .panel { margin-top: 16px; }
            .totals { justify-content: stretch; }
            .totals-card { width: 100%; }
        }
        @media print {
            .actions { display: none; }
            body { padding: 0; background: #fff; }
            .sheet { box-shadow: none; }
        }
    </style>
</head>
<body>
    @php
        $companyName = 'eComNest Soultions';
        $logoPath = asset('logo.jpeg');
    @endphp

    <div class="wrap">
        <div class="actions">
            <button class="print-btn" onclick="window.print()">Print Invoice</button>
        </div>

        <div class="sheet">
            <div class="hero">
                <div class="hero-grid">
                    <div class="brand">
                        <div class="logo-wrap">
                            <img src="{{ $logoPath }}" alt="{{ $companyName }} logo">
                        </div>
                        <div>
                            <p class="eyebrow">Issued By</p>
                            <h1 class="title">{{ $companyName }}</h1>
                            <p class="subtitle">Professional invoice generated from the eComNest admin workspace for ecommerce delivery, support, and growth services.</p>
                        </div>
                    </div>

                    <div class="meta-grid">
                        <div class="meta-card">
                            <span>Invoice No</span>
                            <strong>{{ $invoice->invoice_number }}</strong>
                        </div>
                        <div class="meta-card">
                            <span>Status</span>
                            <strong>{{ $invoice->status->label() }}</strong>
                        </div>
                        <div class="meta-card">
                            <span>Issue Date</span>
                            <strong>{{ $invoice->issue_date?->format('d M Y') ?: 'Not set' }}</strong>
                        </div>
                        <div class="meta-card">
                            <span>Due Date</span>
                            <strong>{{ $invoice->due_date?->format('d M Y') ?: 'Not set' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="split">
                    <div class="panel slate">
                        <p class="panel-label">From</p>
                        <h3>{{ $companyName }}</h3>
                        <p>Digital commerce services and implementation support</p>
                        <p>Managed through the internal admin panel</p>
                    </div>

                    <div class="panel orange">
                        <p class="panel-label">Bill To</p>
                        <h3>{{ $invoice->customer?->name ?: 'N/A' }}</h3>
                        <p>{{ $invoice->customer?->company_name ?: 'No company name provided' }}</p>
                        <p>{{ $invoice->customer?->email ?: 'No email provided' }}</p>
                        <p>{{ $invoice->customer?->phone ?: 'No phone provided' }}</p>
                        <p>{{ $invoice->customer?->address ?: 'No address provided' }}</p>
                    </div>
                </div>

                <div class="project">
                    <p class="panel-label" style="color:#0369a1;">Project Link</p>
                    <strong>{{ $invoice->project?->project_name ?: 'Customer-level invoice' }}</strong>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->items as $item)
                            <tr>
                                <td>
                                    <div class="item-title">{{ $item->name }}</div>
                                    <div class="item-note">Invoice line item</div>
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rs {{ number_format((float) $item->price, 2) }}</td>
                                <td><strong>Rs {{ number_format((float) $item->total, 2) }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="totals">
                    <div class="totals-card">
                        <div class="total-row">
                            <span>Subtotal</span>
                            <strong>Rs {{ number_format((float) $invoice->subtotal_amount, 2) }}</strong>
                        </div>
                        <div class="total-row">
                            <span>Discount</span>
                            <strong>Rs {{ number_format((float) $invoice->discount, 2) }}</strong>
                        </div>
                        <div class="total-row grand-total">
                            <span>Final Amount</span>
                            <span>Rs {{ number_format((float) $invoice->final_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="notes">
                    <p>Notes</p>
                    <p>{{ $invoice->notes ?: 'No notes added for this invoice.' }}</p>
                </div>

                <div class="footer">
                    This invoice was generated by <strong>{{ $companyName }}</strong>.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
