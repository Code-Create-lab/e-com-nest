@php
    $brand = config('app.name', 'Self Admin Panel');
    $contactEmail = config('mail.from.address');
    $hasDirectEmail = filled($contactEmail) && ! str_contains($contactEmail, 'example.com');
    $contactHref = $hasDirectEmail
        ? 'mailto:' . $contactEmail . '?subject=' . rawurlencode($brand . ' Project Brief')
        : route('login');

    $heroStats = [
        ['label' => 'Projects shipped',     'value' => '120+'],
        ['label' => 'Avg. time to launch',  'value' => '6 wks'],
        ['label' => 'Client retention',     'value' => '94%'],
    ];

    $trustLogos = ['NORDIC', 'ATELIER CO.', 'FOUNDRY', 'HARBOR', 'MONOLITH', 'STOCKROOM'];

    $ecommerceBullets = [
        'Clean home, collection, product, cart, and checkout flows designed to increase trust and reduce friction.',
        'Packaging-focused presentation blocks that make products feel premium before the customer even clicks buy.',
        'Better merchandising structure for offers, bundles, launch campaigns, and repeat purchase journeys.',
    ];

    $webAppBullets = [
        'Custom web apps for order handling, inventory visibility, client access, booking systems, and internal workflows.',
        'Dashboard patterns that help staff, vendors, and admins work faster without interface clutter.',
        'Connected operational views for packed products, shipping status, fulfillment, and reporting.',
    ];

    $serviceCards = [
        [
            'icon' => 'box',
            'title' => 'Packed product visuals',
            'copy' => 'Show the box, label, texture, and product story in a way that feels deliberate and premium.',
        ],
        [
            'icon' => 'truck',
            'title' => 'Logistics-ready structure',
            'copy' => 'Useful for brands that ship at scale and need warehouse, delivery, and order-state communication built in.',
        ],
        [
            'icon' => 'palette',
            'title' => 'Black and white identity',
            'copy' => 'A lighter monochrome system keeps the page clean, modern, and easier to trust at first glance.',
        ],
        [
            'icon' => 'grid',
            'title' => 'Modern app surfaces',
            'copy' => 'Client portals and custom panels stay sharp while still feeling practical for day-to-day use.',
        ],
    ];

    $process = [
        [
            'number' => '01',
            'title' => 'Position the offer',
            'copy' => 'We shape the message, sections, and conversion path before visual polish starts driving decisions.',
        ],
        [
            'number' => '02',
            'title' => 'Design the product look',
            'copy' => 'We build the page language, visual boxes, imagery style, and content rhythm around your business model.',
        ],
        [
            'number' => '03',
            'title' => 'Connect the workflow',
            'copy' => 'Storefront, dashboard, order flow, logistics states, and integrations are handled as one system.',
        ],
        [
            'number' => '04',
            'title' => 'Launch with room to grow',
            'copy' => 'The final structure is ready for new categories, campaigns, reports, and internal tooling later.',
        ],
    ];

    $segments = [
        'Ecommerce websites',
        'Packed product presentation',
        'Logistics workflows',
        'Client portals',
        'Custom web applications',
        'Admin dashboards',
    ];

    $techStack = [
        'Laravel', 'Livewire', 'Tailwind', 'Vue', 'Inertia',
        'PostgreSQL', 'Redis', 'Stripe', 'AWS', 'Cloudflare',
    ];

    $testimonials = [
        [
            'quote' => 'They shipped faster than any agency we tried. The admin workflow alone saved us two full-time ops hires.',
            'name'  => 'Morgan Hale',
            'role'  => 'Founder, Harbor Supply',
            'mark'  => 'MH',
        ],
        [
            'quote' => 'The packed product visuals completely changed how our catalog feels. AOV went up within the first month.',
            'name'  => 'Jules Varga',
            'role'  => 'Ecommerce Lead, Atelier Co.',
            'mark'  => 'JV',
        ],
        [
            'quote' => 'Our order flow used to live in spreadsheets. Now logistics, packing, and reporting sit in one clean panel.',
            'name'  => 'Riya Desai',
            'role'  => 'Operations, Stockroom',
            'mark'  => 'RD',
        ],
    ];

    $faqs = [
        [
            'q' => 'How long does a typical build take?',
            'a' => 'Most ecommerce launches land in four to eight weeks. Larger builds with custom admin, portals, or logistics tooling usually run eight to twelve.',
        ],
        [
            'q' => 'Do you handle both the storefront and the admin side?',
            'a' => 'Yes. The storefront, admin dashboard, order flow, and any client portal are designed and built as one connected system.',
        ],
        [
            'q' => 'Can you integrate with our existing tools?',
            'a' => 'We integrate with Stripe, Shopify, Shiprocket, Delhivery, Zoho, HubSpot, Google Sheets, and most standard REST or webhook APIs.',
        ],
        [
            'q' => 'What happens after launch?',
            'a' => 'You can keep us on retainer for ongoing design, new campaigns, new categories, and internal tooling, or take the codebase and move independently.',
        ],
    ];

    $footerLinks = [
        'Company'  => [['Solutions', '#solutions'], ['Process', '#process'], ['Visual system', '#visuals']],
        'Services' => [['Ecommerce websites', '#solutions'], ['Custom web apps', '#solutions'], ['Admin dashboards', '#solutions']],
        'Account'  => [['Client login', route('login')], ['Start a project', '#contact']],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $brand }} — Ecommerce & Custom Web Application Development</title>
        <meta
            name="description"
            content="Light, modern ecommerce websites and custom web applications with packed product visuals, logistics-ready structure, and clean admin workflows."
        >
        <meta name="theme-color" content="#fafaf9">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Manrope:wght@400;500;600;700;800&display=swap"
            rel="stylesheet"
        >

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body data-marketing-page class="min-h-screen bg-stone-50 text-zinc-950 antialiased">
        <div aria-hidden="true" class="pointer-events-none fixed inset-0 -z-20 bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,0.95),rgba(244,244,245,0.78),rgba(228,228,231,0.35))]"></div>
        <div aria-hidden="true" class="marketing-grid fixed inset-0 -z-10"></div>

        {{-- Announcement bar --}}
        <div class="marketing-announcement">
            <div class="mx-auto flex max-w-7xl items-center justify-center gap-2 px-4 py-2 text-xs font-medium tracking-wide text-zinc-600 sm:px-6 lg:px-8">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                </span>
                <span class="hidden sm:inline">Now booking Q2 launches —</span>
                <span class="sm:ml-1">limited build slots this quarter.</span>
                <a href="#contact" class="ml-2 inline-flex items-center gap-1 font-semibold text-zinc-950 underline-offset-4 hover:underline">
                    Reserve a slot
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                </a>
            </div>
        </div>

        {{-- Header --}}
        <header data-site-header class="sticky top-0 z-40">
            <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
                <div class="glass-panel flex items-center justify-between gap-4 rounded-full px-4 py-3 sm:px-6">
                    <a href="#top" class="flex items-center gap-3">
                        <span class="marketing-mark">SA</span>
                        <div class="leading-tight">
                            <p class="text-sm font-semibold text-zinc-950">{{ $brand }}</p>
                            <p class="text-[0.68rem] uppercase tracking-[0.24em] text-zinc-500">Ecommerce & Apps Studio</p>
                        </div>
                    </a>

                    <nav aria-label="Primary" class="hidden items-center gap-7 text-sm text-zinc-600 lg:flex">
                        <a class="marketing-nav-link" href="#solutions">Solutions</a>
                        <a class="marketing-nav-link" href="#visuals">Visual system</a>
                        <a class="marketing-nav-link" href="#process">Process</a>
                        <a class="marketing-nav-link" href="#work">Work</a>
                        <a class="marketing-nav-link" href="#faq">FAQ</a>
                    </nav>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="hidden text-sm text-zinc-600 transition-colors duration-200 hover:text-zinc-950 sm:inline-flex">
                            Client login
                        </a>
                        <a href="#contact" class="marketing-primary-btn px-4 py-2 text-sm">
                            Start project
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                        </a>
                        <button type="button" data-menu-toggle aria-expanded="false" aria-controls="mobile-menu" class="marketing-menu-btn lg:hidden">
                            <span class="sr-only">Toggle menu</span>
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Mobile menu --}}
                <div id="mobile-menu" data-menu hidden class="glass-panel mt-3 rounded-3xl p-4 lg:hidden">
                    <nav aria-label="Mobile" class="grid gap-1 text-sm font-medium text-zinc-700">
                        <a class="marketing-mobile-link" href="#solutions">Solutions</a>
                        <a class="marketing-mobile-link" href="#visuals">Visual system</a>
                        <a class="marketing-mobile-link" href="#process">Process</a>
                        <a class="marketing-mobile-link" href="#work">Work</a>
                        <a class="marketing-mobile-link" href="#faq">FAQ</a>
                        <a class="marketing-mobile-link" href="{{ route('login') }}">Client login</a>
                    </nav>
                </div>
            </div>
        </header>

        <main id="top">
            {{-- Hero --}}
            <section class="px-4 pb-16 pt-10 sm:px-6 lg:px-8 lg:pb-22 lg:pt-16">
                <div class="mx-auto grid max-w-7xl gap-12 lg:grid-cols-[0.96fr_1.04fr] lg:items-center">
                    <div class="max-w-2xl">
                        <div data-reveal data-delay="0" class="reveal-up">
                            <span class="marketing-badge">
                                <span class="h-1.5 w-1.5 rounded-full bg-zinc-950"></span>
                                Ecommerce + custom apps, designed as one system
                            </span>
                        </div>

                        <h1
                            data-reveal
                            data-delay="80"
                            class="marketing-display reveal-up mt-6 text-[2.75rem] leading-[0.95] tracking-[-0.05em] text-zinc-950 sm:text-6xl lg:text-7xl"
                        >
                            Premium storefronts <em class="italic text-zinc-700">with</em> logistics and admin built in.
                        </h1>

                        <p
                            data-reveal
                            data-delay="150"
                            class="reveal-up mt-6 max-w-xl text-lg leading-8 text-zinc-600 sm:text-xl"
                        >
                            A light, modern, product-first direction that shows what matters visually — packed products, order flow, shipping movement — and the operational system behind the sale.
                        </p>

                        <div data-reveal data-delay="220" class="reveal-up mt-8 flex flex-col gap-3 sm:flex-row">
                            <a href="#contact" class="marketing-primary-btn justify-center sm:justify-start">
                                Build my website
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                            </a>
                            <a href="#solutions" class="marketing-secondary-btn justify-center sm:justify-start">
                                See services
                            </a>
                        </div>

                        <div data-reveal data-delay="280" class="reveal-up mt-8 flex flex-wrap items-center gap-x-6 gap-y-3 text-xs font-medium text-zinc-500">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 5 5L20 7"/></svg>
                                No long lock-ins
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 5 5L20 7"/></svg>
                                Own the code and hosting
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 5 5L20 7"/></svg>
                                Fixed timeline, fixed scope
                            </div>
                        </div>

                        <div data-reveal data-delay="340" class="reveal-up mt-10 grid gap-4 sm:grid-cols-3">
                            @foreach ($heroStats as $stat)
                                <div class="marketing-stat-card">
                                    <p class="text-xs uppercase tracking-[0.2em] text-zinc-500">{{ $stat['label'] }}</p>
                                    <p class="marketing-display mt-2 text-3xl tracking-[-0.04em] text-zinc-950">{{ $stat['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div data-reveal data-delay="180" class="reveal-up">
                        <div class="marketing-hero-frame">
                            <div class="relative rounded-[28px] border border-black/10 bg-white p-3 shadow-[0_25px_70px_rgba(0,0,0,0.09)]">
                                <div class="absolute inset-x-8 -top-3 h-6 rounded-b-3xl bg-black/5"></div>
                                <img
                                    src="{{ asset('images/packed-products.svg') }}"
                                    alt="Packed ecommerce products arranged in monochrome shipping boxes"
                                    class="h-auto w-full rounded-[22px]"
                                    loading="eager"
                                >
                            </div>

                            <div class="marketing-floating-card left-[-0.75rem] top-[8%]">
                                <div class="flex items-center gap-2">
                                    <span class="marketing-dot"></span>
                                    <p class="text-[0.68rem] uppercase tracking-[0.22em] text-zinc-500">Visual focus</p>
                                </div>
                                <p class="mt-3 text-lg font-semibold text-zinc-950">Packed products</p>
                                <p class="mt-2 text-sm leading-6 text-zinc-600">Packaging, labels, and box quality shown immediately.</p>
                            </div>

                            <div class="marketing-floating-image">
                                <img
                                    src="{{ asset('images/logistics-fulfillment.svg') }}"
                                    alt="Logistics and fulfillment workflow illustration for ecommerce operations"
                                    class="h-auto w-full rounded-[20px]"
                                    loading="lazy"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Trust logo strip --}}
            <section aria-label="Trusted by" class="px-4 pb-6 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    <div data-reveal data-delay="0" class="reveal-up">
                        <p class="text-center text-xs uppercase tracking-[0.3em] text-zinc-500">Trusted by product-led teams</p>
                        <div class="mt-5 grid grid-cols-2 items-center justify-items-center gap-6 sm:grid-cols-3 lg:grid-cols-6">
                            @foreach ($trustLogos as $logo)
                                <span class="marketing-logo">{{ $logo }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            {{-- Segments strip --}}
            <section class="px-4 py-10 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    <div data-reveal data-delay="0" class="reveal-up glass-panel rounded-[34px] px-6 py-6 sm:px-8">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                            <div class="max-w-3xl">
                                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-zinc-500">Built for product & operations</p>
                                <p class="mt-3 text-base leading-7 text-zinc-600 sm:text-lg">
                                    Especially useful when the business needs both customer-facing design and internal order, packing, inventory, or logistics clarity.
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($segments as $segment)
                                    <span class="marketing-pill">{{ $segment }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Solutions --}}
            <section id="solutions" class="px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
                <div class="mx-auto max-w-7xl">
                    <div data-reveal data-delay="0" class="reveal-up max-w-3xl">
                        <span class="marketing-badge">Core solutions</span>
                        <h2 class="marketing-display mt-6 text-4xl leading-[0.95] tracking-[-0.04em] text-zinc-950 sm:text-5xl">
                            Ecommerce on the front. <em class="italic text-zinc-700">Logistics and workflow</em> support underneath.
                        </h2>
                        <p class="mt-5 text-lg leading-8 text-zinc-600">
                            The page should not only look attractive. It should also communicate that the business can sell, pack, ship, report, and scale without looking chaotic.
                        </p>
                    </div>

                    <div class="mt-10 grid gap-6 xl:grid-cols-2">
                        <article data-reveal data-delay="70" class="glass-panel-strong reveal-up rounded-[34px] p-6 sm:p-8">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-zinc-500">Ecommerce website development</p>
                                    <h3 class="mt-3 text-3xl font-semibold tracking-[-0.03em] text-zinc-950">
                                        Product-led websites that feel premium before the customer scrolls far.
                                    </h3>
                                </div>
                                <span class="marketing-tag">Packed product</span>
                            </div>

                            <div class="mt-7 overflow-hidden rounded-[28px] border border-black/10 bg-white">
                                <img src="{{ asset('images/packed-products.svg') }}" alt="Monochrome packaged products and ecommerce box image" class="h-auto w-full" loading="lazy">
                            </div>

                            <ul class="mt-7 grid gap-4 text-sm leading-7 text-zinc-600">
                                @foreach ($ecommerceBullets as $bullet)
                                    <li class="flex items-start gap-3">
                                        <span class="marketing-check" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 5 5L20 7"/></svg>
                                        </span>
                                        <span>{{ $bullet }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </article>

                        <article data-reveal data-delay="140" class="glass-panel-strong reveal-up rounded-[34px] p-6 sm:p-8">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-zinc-500">Custom web applications</p>
                                    <h3 class="mt-3 text-3xl font-semibold tracking-[-0.03em] text-zinc-950">
                                        Systems for inventory, fulfillment, internal workflow, and client-side visibility.
                                    </h3>
                                </div>
                                <span class="marketing-tag">Logistics</span>
                            </div>

                            <div class="mt-7 overflow-hidden rounded-[28px] border border-black/10 bg-white">
                                <img src="{{ asset('images/logistics-fulfillment.svg') }}" alt="Logistics, warehouse, and order flow image for a custom web application" class="h-auto w-full" loading="lazy">
                            </div>

                            <ul class="mt-7 grid gap-4 text-sm leading-7 text-zinc-600">
                                @foreach ($webAppBullets as $bullet)
                                    <li class="flex items-start gap-3">
                                        <span class="marketing-check" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 5 5L20 7"/></svg>
                                        </span>
                                        <span>{{ $bullet }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </article>
                    </div>
                </div>
            </section>

            {{-- Visual system / service cards --}}
            <section id="visuals" class="px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
                <div class="mx-auto max-w-7xl">
                    <div data-reveal data-delay="0" class="reveal-up flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-3xl">
                            <span class="marketing-badge">Visual system</span>
                            <h2 class="marketing-display mt-6 text-4xl leading-[0.95] tracking-[-0.04em] text-zinc-950 sm:text-5xl">
                                More product boxes, more visual proof, a cleaner monochrome layout.
                            </h2>
                        </div>
                        <p class="max-w-xl text-base leading-7 text-zinc-600 sm:text-lg">
                            The light theme keeps everything readable, while the box-based composition helps ecommerce, packaging, and shipping businesses feel organized instead of noisy.
                        </p>
                    </div>

                    <div class="mt-10 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                        @foreach ($serviceCards as $card)
                            <article data-reveal data-delay="{{ $loop->index * 80 }}" class="reveal-up marketing-service-box">
                                <div class="marketing-icon-wrap">
                                    @switch($card['icon'])
                                        @case('box')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 12 3l9 4.5M3 7.5V16.5L12 21m-9-13.5 9 4.5m9-4.5V16.5L12 21m0-9v9"/></svg>
                                            @break
                                        @case('truck')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h11v9H3zM14 10h4l3 3v3h-7zM7.5 19a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm9 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/></svg>
                                            @break
                                        @case('palette')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3a9 9 0 1 0 0 18c1.1 0 2-.9 2-2 0-.6-.2-1.1-.6-1.5-.4-.4-.6-.9-.6-1.5 0-1.1.9-2 2-2h2a4 4 0 0 0 4-4c0-4-4-7-9-7Z"/><circle cx="7.5" cy="10.5" r="1"/><circle cx="12" cy="7.5" r="1"/><circle cx="16.5" cy="10.5" r="1"/></svg>
                                            @break
                                        @case('grid')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z"/></svg>
                                            @break
                                    @endswitch
                                </div>
                                <h3 class="mt-5 text-2xl font-semibold tracking-[-0.03em] text-zinc-950">{{ $card['title'] }}</h3>
                                <p class="mt-4 text-base leading-7 text-zinc-600">{{ $card['copy'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- Process --}}
            <section id="process" class="px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
                <div class="mx-auto grid max-w-7xl gap-8 lg:grid-cols-[0.88fr_1.12fr]">
                    <div data-reveal data-delay="0" class="reveal-up">
                        <span class="marketing-badge">Delivery process</span>
                        <h2 class="marketing-display mt-6 text-4xl leading-[0.95] tracking-[-0.04em] text-zinc-950 sm:text-5xl">
                            Website and workflow designed together — not as separate ideas.
                        </h2>
                        <p class="mt-5 max-w-xl text-lg leading-8 text-zinc-600">
                            That is what makes the result look intentional. Visuals, content, product presentation, and app structure all reinforce the same story.
                        </p>

                        <dl class="mt-8 grid max-w-md gap-4 sm:grid-cols-2">
                            <div class="marketing-mini-stat">
                                <dt class="text-xs uppercase tracking-[0.2em] text-zinc-500">Discovery</dt>
                                <dd class="marketing-display mt-1 text-2xl text-zinc-950">1 week</dd>
                            </div>
                            <div class="marketing-mini-stat">
                                <dt class="text-xs uppercase tracking-[0.2em] text-zinc-500">Ship to prod</dt>
                                <dd class="marketing-display mt-1 text-2xl text-zinc-950">4–8 weeks</dd>
                            </div>
                        </dl>
                    </div>

                    <ol class="relative space-y-4">
                        <span aria-hidden="true" class="marketing-process-line"></span>
                        @foreach ($process as $step)
                            <li data-reveal data-delay="{{ 70 + ($loop->index * 90) }}" class="reveal-up">
                                <article class="glass-panel rounded-[30px] p-6 sm:p-7">
                                    <div class="flex flex-col gap-5 sm:flex-row">
                                        <div class="marketing-step-number">{{ $step['number'] }}</div>
                                        <div>
                                            <h3 class="text-2xl font-semibold tracking-[-0.03em] text-zinc-950">{{ $step['title'] }}</h3>
                                            <p class="mt-3 text-base leading-7 text-zinc-600">{{ $step['copy'] }}</p>
                                        </div>
                                    </div>
                                </article>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </section>

            {{-- Testimonials / Work --}}
            <section id="work" class="px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
                <div class="mx-auto max-w-7xl">
                    <div data-reveal data-delay="0" class="reveal-up flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-3xl">
                            <span class="marketing-badge">Client voices</span>
                            <h2 class="marketing-display mt-6 text-4xl leading-[0.95] tracking-[-0.04em] text-zinc-950 sm:text-5xl">
                                What founders and operators say after launch.
                            </h2>
                        </div>
                        <p class="max-w-xl text-base leading-7 text-zinc-600 sm:text-lg">
                            The pages below shipped to production with the same direction — clean monochrome surface, product-first boxes, and a connected admin layer.
                        </p>
                    </div>

                    <div class="mt-10 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($testimonials as $t)
                            <figure data-reveal data-delay="{{ $loop->index * 90 }}" class="reveal-up marketing-testimonial">
                                <div class="marketing-quote-mark" aria-hidden="true">&ldquo;</div>
                                <blockquote class="mt-3 text-base leading-7 text-zinc-700">
                                    {{ $t['quote'] }}
                                </blockquote>
                                <figcaption class="mt-6 flex items-center gap-3">
                                    <span class="marketing-avatar">{{ $t['mark'] }}</span>
                                    <span>
                                        <span class="block text-sm font-semibold text-zinc-950">{{ $t['name'] }}</span>
                                        <span class="block text-xs text-zinc-500">{{ $t['role'] }}</span>
                                    </span>
                                </figcaption>
                            </figure>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- Tech stack --}}
            <section aria-label="Tech stack" class="px-4 pb-20 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    <div data-reveal data-delay="0" class="reveal-up glass-panel rounded-[34px] px-6 py-8 sm:px-8">
                        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                            <div class="max-w-xl">
                                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-zinc-500">Stack & infrastructure</p>
                                <p class="mt-3 text-base leading-7 text-zinc-600 sm:text-lg">
                                    Opinionated, proven tooling. Every choice is made for long-term maintainability, not short-term novelty.
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($techStack as $tech)
                                    <span class="marketing-chip">{{ $tech }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- FAQ --}}
            <section id="faq" class="px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
                <div class="mx-auto grid max-w-7xl gap-10 lg:grid-cols-[0.88fr_1.12fr]">
                    <div data-reveal data-delay="0" class="reveal-up">
                        <span class="marketing-badge">FAQ</span>
                        <h2 class="marketing-display mt-6 text-4xl leading-[0.95] tracking-[-0.04em] text-zinc-950 sm:text-5xl">
                            Answers to the questions we hear first.
                        </h2>
                        <p class="mt-5 max-w-xl text-lg leading-8 text-zinc-600">
                            Something missing? <a href="#contact" class="font-semibold text-zinc-950 underline underline-offset-4">Ask us directly</a> — we reply within one working day.
                        </p>
                    </div>

                    <div class="grid gap-3">
                        @foreach ($faqs as $i => $faq)
                            <details data-reveal data-delay="{{ $i * 80 }}" class="reveal-up marketing-faq-item" @if($i === 0) open @endif>
                                <summary class="marketing-faq-summary">
                                    <span>{{ $faq['q'] }}</span>
                                    <span class="marketing-faq-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                                    </span>
                                </summary>
                                <div class="marketing-faq-body">{{ $faq['a'] }}</div>
                            </details>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- Contact / CTA --}}
            <section id="contact" class="px-4 pb-24 pt-8 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    <div data-reveal data-delay="0" class="reveal-up marketing-cta-panel">
                        <div aria-hidden="true" class="marketing-cta-glow"></div>
                        <div class="relative grid gap-10 p-8 sm:p-10 lg:grid-cols-[1fr_360px] lg:items-center lg:p-12">
                            <div>
                                <span class="marketing-badge marketing-badge-invert">Ready to build</span>
                                <h2 class="marketing-display mt-6 text-4xl leading-[0.95] tracking-[-0.04em] text-white sm:text-5xl">
                                    Let's ship a lighter, black-and-white storefront — with real product and logistics visuals.
                                </h2>
                                <p class="mt-5 max-w-2xl text-lg leading-8 text-zinc-300">
                                    Ecommerce development, packed product storytelling, or any custom web application that needs a clean modern surface. One team, one timeline, one shipped result.
                                </p>

                                <ul class="mt-8 grid gap-3 sm:grid-cols-2">
                                    <li class="flex items-center gap-3 text-sm text-zinc-200">
                                        <span class="marketing-check marketing-check-dark"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 5 5L20 7"/></svg></span>
                                        Fixed scope, fixed price
                                    </li>
                                    <li class="flex items-center gap-3 text-sm text-zinc-200">
                                        <span class="marketing-check marketing-check-dark"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 5 5L20 7"/></svg></span>
                                        Weekly demo calls
                                    </li>
                                    <li class="flex items-center gap-3 text-sm text-zinc-200">
                                        <span class="marketing-check marketing-check-dark"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 5 5L20 7"/></svg></span>
                                        Full source code handover
                                    </li>
                                    <li class="flex items-center gap-3 text-sm text-zinc-200">
                                        <span class="marketing-check marketing-check-dark"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 5 5L20 7"/></svg></span>
                                        Optional retainer after launch
                                    </li>
                                </ul>
                            </div>

                            <div class="marketing-cta-card">
                                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-zinc-500">Next step</p>
                                <p class="mt-4 text-base leading-7 text-zinc-600">
                                    Share your project type, product category, and whether you need admin, shipping, or client portal features.
                                </p>

                                @if ($hasDirectEmail)
                                    <a href="{{ $contactHref }}" class="marketing-primary-btn mt-6 w-full justify-center">
                                        Send project brief
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                                    </a>
                                    <p class="mt-3 text-center text-xs text-zinc-500">Replies within 1 working day — {{ $contactEmail }}</p>
                                @else
                                    <a href="{{ route('login') }}" class="marketing-primary-btn mt-6 w-full justify-center">
                                        Request private demo
                                    </a>
                                    <p class="mt-3 text-center text-xs text-zinc-500">Replies within 1 working day.</p>
                                @endif

                                <a href="{{ route('login') }}" class="marketing-secondary-btn mt-3 w-full justify-center">
                                    Client login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        {{-- Footer --}}
        <footer class="px-4 pb-10 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="glass-panel rounded-[34px] px-6 py-10 sm:px-8">
                    <div class="grid gap-10 lg:grid-cols-[1.2fr_2fr]">
                        <div>
                            <div class="flex items-center gap-3">
                                <span class="marketing-mark">SA</span>
                                <div class="leading-tight">
                                    <p class="text-sm font-semibold text-zinc-950">{{ $brand }}</p>
                                    <p class="text-[0.68rem] uppercase tracking-[0.24em] text-zinc-500">Ecommerce & Apps Studio</p>
                                </div>
                            </div>
                            <p class="mt-4 max-w-sm text-sm leading-6 text-zinc-600">
                                A small, senior team shipping monochrome storefronts and connected admin tools for product-led businesses.
                            </p>
                        </div>

                        <div class="grid gap-8 sm:grid-cols-3">
                            @foreach ($footerLinks as $heading => $links)
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-zinc-500">{{ $heading }}</p>
                                    <ul class="mt-4 grid gap-2 text-sm text-zinc-700">
                                        @foreach ($links as [$label, $href])
                                            <li><a class="marketing-footer-link" href="{{ $href }}">{{ $label }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-10 flex flex-col items-start justify-between gap-3 border-t border-black/10 pt-6 text-xs text-zinc-500 sm:flex-row sm:items-center">
                        <p>&copy; {{ date('Y') }} {{ $brand }}. All rights reserved.</p>
                        <div class="flex items-center gap-4">
                            <a href="#top" class="marketing-footer-link">Back to top ↑</a>
                            @if ($hasDirectEmail)
                                <a href="{{ $contactHref }}" class="marketing-footer-link">{{ $contactEmail }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
