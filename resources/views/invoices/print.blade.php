<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $invoice->invoice_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #0f172a;
            --muted: #64748b;
            --soft: #94a3b8;
            --line: #e4e7eb;
            --line-2: #cbd5e1;
            --bg: #f1f5f9;
            --navy: #1e3a5f;
            --navy-tint: #eef2f7;
            --paid: #059669;
            --paid-tint: #ecfdf5;
        }
        * { box-sizing: border-box; }
        html { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        body {
            margin: 0;
            padding: 28px;
            font-family: "IBM Plex Sans", "Segoe UI", Arial, sans-serif;
            color: var(--ink);
            background: var(--bg);
            font-size: 13px;
            line-height: 1.5;
        }
        .wrap { max-width: 840px; margin: 0 auto; }

        /* ---- toolbar ---- */
        .actions { margin-bottom: 18px; display: flex; gap: 10px; }
        .print-btn {
            border: 0; border-radius: 8px; cursor: pointer;
            background: var(--navy); color: #fff;
            padding: 11px 22px; font-weight: 600; font-size: 13px;
            display: inline-flex; align-items: center; gap: 8px;
            transition: background .18s ease, transform .18s ease;
        }
        .print-btn:hover { background: #16314f; transform: translateY(-1px); }
        .print-btn svg { width: 16px; height: 16px; }

        .sheet {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 34px rgba(15, 23, 42, 0.08);
        }

        /* ---- header band ---- */
        .head {
            display: table; width: 100%;
            background: var(--navy); color: #fff;
            padding: 30px 40px;
        }
        .head .brand, .head .doc { display: table-cell; vertical-align: middle; }
        .head .doc { text-align: right; }
        .brand-row { display: flex; align-items: center; gap: 14px; }
        .logo {
            width: 72px; height: 72px; border-radius: 12px;
            background: #fff; object-fit: contain; padding: 4px;
        }
        .brand-name { font-size: 22px; font-weight: 700; letter-spacing: .01em; }
        .brand-tag { font-size: 11.5px; color: #c7d4e3; margin-top: 2px; }
        .doc h1 {
            margin: 0; font-size: 26px; font-weight: 700;
            letter-spacing: .12em; text-transform: uppercase;
        }
        .doc .sub { font-size: 11px; color: #c7d4e3; letter-spacing: .08em; text-transform: uppercase; margin-top: 4px; }
        .doc .inv-no { margin-top: 10px; font-size: 13px; font-weight: 600; }
        .doc .inv-no span { color: #c7d4e3; font-weight: 400; }

        /* ---- meta strip ---- */
        .meta {
            display: table; width: 100%;
            background: var(--navy-tint);
            border-bottom: 1px solid var(--line);
        }
        .meta .cell {
            display: table-cell; width: 25%;
            padding: 14px 40px; vertical-align: top;
            border-right: 1px solid #dde5ee;
        }
        .meta .cell:last-child { border-right: 0; }
        .meta .k { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; color: var(--muted); }
        .meta .v { font-size: 13px; font-weight: 600; margin-top: 3px; color: var(--ink); }
        .status-pill {
            display: inline-block; margin-top: 3px;
            padding: 3px 12px; border-radius: 999px;
            font-size: 11px; font-weight: 600; letter-spacing: .02em;
            background: var(--paid-tint); color: var(--paid);
            border: 1px solid #a7f3d0;
        }

        /* ---- body ---- */
        .body { padding: 32px 40px 36px; }

        .parties { display: table; width: 100%; margin-bottom: 30px; }
        .parties .party { display: table-cell; width: 50%; vertical-align: top; }
        .parties .party.to { padding-right: 22px; }
        .parties .party.from { padding-left: 22px; border-left: 1px solid var(--line); }
        .party .label {
            font-size: 10px; font-weight: 700; letter-spacing: .08em;
            text-transform: uppercase; color: var(--navy); margin-bottom: 8px;
        }
        .party .name { font-size: 15px; font-weight: 700; color: var(--ink); }
        .party p { margin: 2px 0; font-size: 12.5px; color: var(--muted); }
        .party .kv b { color: var(--ink); font-weight: 600; }

        /* ---- items table ---- */
        table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        thead th {
            background: var(--navy); color: #fff;
            padding: 11px 14px;
            font-size: 10.5px; font-weight: 600;
            text-transform: uppercase; letter-spacing: .05em;
            text-align: left;
        }
        thead th:first-child { border-top-left-radius: 8px; }
        thead th:last-child { border-top-right-radius: 8px; }
        thead th.num, tbody td.num { text-align: right; }
        thead th.center, tbody td.center { text-align: center; }
        tbody td {
            padding: 13px 14px; font-size: 12.5px;
            border-bottom: 1px solid var(--line); vertical-align: top;
        }
        tbody tr:nth-child(even) td { background: #f8fafc; }
        tbody td.num { font-variant-numeric: tabular-nums; }
        .item-name { font-weight: 600; color: var(--ink); }

        /* ---- totals ---- */
        .totals { display: table; width: 100%; margin-top: 22px; }
        .totals .spacer { display: table-cell; width: 52%; vertical-align: top; }
        .totals .box { display: table-cell; width: 48%; vertical-align: top; }
        .totals .row { display: table; width: 100%; padding: 9px 14px; }
        .totals .row .t-label { display: table-cell; font-size: 12.5px; color: var(--muted); }
        .totals .row .t-val { display: table-cell; text-align: right; font-size: 12.5px; font-weight: 600; font-variant-numeric: tabular-nums; }
        .totals .sep { border-bottom: 1px solid var(--line); }
        .totals .grand {
            display: table; width: 100%; margin-top: 8px;
            background: var(--navy); color: #fff;
            border-radius: 8px; padding: 14px 16px;
        }
        .totals .grand .t-label { display: table-cell; font-size: 13px; font-weight: 600; letter-spacing: .02em; }
        .totals .grand .t-val { display: table-cell; text-align: right; font-size: 19px; font-weight: 700; font-variant-numeric: tabular-nums; }

        /* ---- payment / notes ---- */
        .pay {
            margin-top: 30px;
            border: 1px solid var(--line); border-left: 3px solid var(--navy);
            background: #f8fafc; border-radius: 8px;
            padding: 16px 20px;
        }
        .pay h4 { margin: 0 0 10px; font-size: 13px; font-weight: 700; color: var(--navy); }
        .pay .line { display: table; width: 100%; }
        .pay .line .k { display: table-cell; font-size: 12.5px; color: var(--muted); }
        .pay .line .v { display: table-cell; text-align: right; font-weight: 600; font-size: 12.5px; }

        .notes { margin-top: 18px; font-size: 12.5px; color: var(--muted); }
        .notes b { color: var(--ink); }

        .fineprint { margin-top: 24px; font-size: 11px; color: var(--soft); text-align: center; }
        .legal {
            margin-top: 14px; padding-top: 14px;
            border-top: 1px solid var(--line);
            font-size: 11px; color: var(--muted); text-align: center;
        }
        .legal b { color: var(--ink); }

        @media screen and (max-width: 720px) {
            body { padding: 14px; }
            .head, .body { padding-left: 22px; padding-right: 22px; }
            .head, .head .brand, .head .doc,
            .parties, .parties .party,
            .totals, .totals .spacer, .totals .box,
            .meta, .meta .cell { display: block; width: 100% !important; }
            .head .doc { text-align: left; margin-top: 18px; }
            .parties .party.from { padding-left: 0; border-left: 0; border-top: 1px solid var(--line); padding-top: 18px; margin-top: 18px; }
            .parties .party.to { padding-right: 0; }
            .meta .cell { border-right: 0; border-bottom: 1px solid #dde5ee; }
            .meta .cell:last-child { border-bottom: 0; }
        }
        @media print {
            @page { margin: 14mm; }
            .actions { display: none; }
            body { padding: 0; background: #fff; font-size: 12px; }
            .wrap { max-width: none; }
            /* overflow:hidden + radius clips page 2 in Chrome print — reset */
            .sheet { box-shadow: none; border: 0; border-radius: 0; overflow: visible; }
            .head, .meta, .pay, .totals .grand { border-radius: 0; }
            thead { display: table-header-group; }
            tr, .totals, .pay, .grand { page-break-inside: avoid; break-inside: avoid; }
            .parties .party { page-break-inside: avoid; break-inside: avoid; }
        }
    </style>
</head>
<body>
    @php
        $companyName = '10xCart';
        $logoPath = asset('logo.jpeg');
        $customer = $invoice->customer;
    @endphp

    <div class="wrap">
        <div class="actions">
            <button class="print-btn" onclick="window.print()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Print / Save PDF
            </button>
        </div>

        <div class="sheet">
            <!-- header band -->
            <div class="head">
                <div class="brand">
                    <div class="brand-row">
                        <img src="{{ $logoPath }}" alt="{{ $companyName }} logo" class="logo">
                        <div>
                            <div class="brand-name">{{ $companyName }}</div>
                            <div class="brand-tag">Digital commerce services &amp; implementation</div>
                        </div>
                    </div>
                </div>
                <div class="doc">
                    <h1>Sale Invoice</h1>
                    <div class="sub">Customer Copy</div>
                    <div class="inv-no"><span>Invoice No.</span> {{ $invoice->invoice_number }}</div>
                </div>
            </div>

            <!-- meta strip -->
            <div class="meta">
                <div class="cell">
                    <div class="k">Invoice Date</div>
                    <div class="v">{{ $invoice->issue_date?->format('d M Y') ?: 'Not set' }}</div>
                </div>
                <div class="cell">
                    <div class="k">Due Date</div>
                    <div class="v">{{ $invoice->due_date?->format('d M Y') ?: 'Not set' }}</div>
                </div>
                <div class="cell">
                    <div class="k">Project</div>
                    <div class="v">{{ $invoice->project?->project_name ?: 'Customer-level' }}</div>
                </div>
                <div class="cell">
                    <div class="k">Status</div>
                    <span class="status-pill">{{ $invoice->status->label() }}</span>
                </div>
            </div>

            <div class="body">
                <!-- parties -->
                <div class="parties">
                    <div class="party to">
                        <div class="label">Bill To</div>
                        <p class="name">{{ $customer?->name ?: 'N/A' }}</p>
                        @if ($customer?->company_name)
                            <p>{{ $customer->company_name }}</p>
                        @endif
                        @if ($customer?->address)
                            <p>{{ $customer->address }}</p>
                        @endif
                        @if ($customer?->phone)
                            <p class="kv"><b>Phone:</b> {{ $customer->phone }}</p>
                        @endif
                        @if ($customer?->email)
                            <p class="kv"><b>Email:</b> {{ $customer->email }}</p>
                        @endif
                    </div>
                    <div class="party from">
                        <div class="label">From</div>
                        <p class="name">{{ $companyName }}</p>
                        <p>Digital commerce services &amp; implementation</p>
                        <p>Managed through the internal admin panel</p>
                    </div>
                </div>

                <!-- items -->
                <table>
                    <thead>
                        <tr>
                            <th class="center" style="width:44px;">Sr.</th>
                            <th>Product Description</th>
                            <th class="center" style="width:64px;">Qty</th>
                            <th class="num" style="width:130px;">Unit Price</th>
                            <th class="num" style="width:140px;">Net Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->items as $item)
                            <tr>
                                <td class="center">{{ $loop->iteration }}</td>
                                <td><span class="item-name">{{ $item->name }}</span></td>
                                <td class="center">{{ $item->quantity }}</td>
                                <td class="num">Rs {{ number_format((float) $item->price, 2) }}</td>
                                <td class="num">Rs {{ number_format((float) $item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- totals -->
                <div class="totals">
                    <div class="spacer"></div>
                    <div class="box">
                        <div class="row sep">
                            <div class="t-label">Subtotal (Before Discount)</div>
                            <div class="t-val">Rs {{ number_format((float) $invoice->subtotal_amount, 2) }}</div>
                        </div>
                        <div class="row">
                            <div class="t-label">Discount</div>
                            <div class="t-val">- Rs {{ number_format((float) $invoice->discount, 2) }}</div>
                        </div>
                        <div class="grand">
                            <div class="t-label">Grand Total</div>
                            <div class="t-val">Rs {{ number_format((float) $invoice->final_amount, 2) }}</div>
                        </div>
                    </div>
                </div>

                <!-- payment -->
                <div class="pay">
                    <h4>Payment Information</h4>
                    <div class="line">
                        <div class="k">Payment Status</div>
                        <div class="v">{{ $invoice->status->label() }}</div>
                    </div>
                </div>

                @if ($invoice->notes)
                    <p class="notes"><b>Notes:</b> {{ $invoice->notes }}</p>
                @endif

                <p class="fineprint">This is a computer generated invoice and does not require signature.</p>

                <div class="legal">
                    <b>{{ $companyName }}</b> — invoice generated from the 10xCart admin workspace.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
