@php
    $brand = config('app.name', 'Self Admin Panel');
    $contactEmail = config('mail.from.address');
    $hasDirectEmail = filled($contactEmail) && ! str_contains($contactEmail, 'example.com');
    $contactHref = $hasDirectEmail
        ? 'mailto:' . $contactEmail . '?subject=' . rawurlencode($brand . ' Project Brief')
        : route('login');

    $heroStats = [
        ['label' => 'Storefront systems', 'value' => 'Ecommerce websites'],
        ['label' => 'Operational layer', 'value' => 'Logistics and admin'],
        ['label' => 'Build style', 'value' => 'Light, modern, premium'],
    ];

    $ecommerceBullets = [
        'Clean home, collection, product, cart, and checkout flows designed to increase trust and reduce friction.',
        'Packaging-focused presentation blocks that make products feel more premium before the customer even clicks buy.',
        'Better merchandising structure for offers, bundles, launch campaigns, and repeat purchase journeys.',
    ];

    $webAppBullets = [
        'Custom web apps for order handling, inventory visibility, client access, booking systems, and internal workflows.',
        'Dashboard patterns that help staff, vendors, and admins work faster without interface clutter.',
        'Connected operational views for packed products, shipping status, fulfillment, and reporting.',
    ];

    $serviceCards = [
        [
            'title' => 'Packed product visuals',
            'copy' => 'Show the box, the label, the texture, and the product story in a way that feels deliberate and premium.',
        ],
        [
            'title' => 'Logistics-ready structure',
            'copy' => 'Useful for brands that ship at scale and need warehouse, delivery, and order-state communication built in.',
        ],
        [
            'title' => 'Black and white identity',
            'copy' => 'A lighter monochrome system keeps the page clean, modern, and easier to trust at first glance.',
        ],
        [
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
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $brand }} | Ecommerce and Web Application Development</title>
        <meta
            name="description"
            content="Light, modern ecommerce websites and custom web applications with packed product visuals, logistics-ready structure, and clean admin workflows."
        >

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

        <header class="sticky top-0 z-40">
            <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
                <div class="glass-panel flex items-center justify-between gap-4 rounded-full px-4 py-3 sm:px-6">
                    <a href="#top" class="flex items-center gap-3">
                        <span class="marketing-mark">SA</span>
                        <div>
                            <p class="text-sm font-semibold text-zinc-950">{{ $brand }}</p>
                            <p class="text-xs uppercase tracking-[0.24em] text-zinc-500">Ecommerce and Apps</p>
                        </div>
                    </a>

                    <nav class="hidden items-center gap-7 text-sm text-zinc-600 lg:flex">
                        <a class="transition-colors duration-200 hover:text-zinc-950" href="#solutions">Solutions</a>
                        <a class="transition-colors duration-200 hover:text-zinc-950" href="#visuals">Visual system</a>
                        <a class="transition-colors duration-200 hover:text-zinc-950" href="#process">Process</a>
                        <a class="transition-colors duration-200 hover:text-zinc-950" href="#contact">Contact</a>
                    </nav>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="hidden text-sm text-zinc-600 transition-colors duration-200 hover:text-zinc-950 sm:inline-flex">
                            Client login
                        </a>
                        <a href="#contact" class="marketing-primary-btn px-4 py-2 text-sm">
                            Start project
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main id="top">
            <section class="px-4 pb-16 pt-10 sm:px-6 lg:px-8 lg:pb-22 lg:pt-16">
                <div class="mx-auto grid max-w-7xl gap-12 lg:grid-cols-[0.96fr_1.04fr] lg:items-center">
                    <div class="max-w-2xl">
                        <div data-reveal data-delay="0" class="reveal-up">
                            <span class="marketing-badge">Light theme. Black and white. Product-first.</span>
                        </div>

                        <h1
                            data-reveal
                            data-delay="80"
                            class="marketing-display reveal-up mt-6 text-5xl leading-[0.93] tracking-[-0.05em] text-zinc-950 sm:text-6xl lg:text-7xl"
                        >
                            Attractive ecommerce websites with product boxes, logistics visuals, and clean custom web apps.
                        </h1>

                        <p
                            data-reveal
                            data-delay="150"
                            class="reveal-up mt-6 max-w-xl text-lg leading-8 text-zinc-600 sm:text-xl"
                        >
                            This direction keeps the website bright, modern, and premium while showing what matters visually: packaged products, order flow, shipping movement, and the business system behind the sale.
                        </p>

                        <div data-reveal data-delay="220" class="reveal-up mt-8 flex flex-col gap-4 sm:flex-row">
                            <a href="#contact" class="marketing-primary-btn justify-center sm:justify-start">
                                Build my website
                            </a>
                            <a href="#solutions" class="marketing-secondary-btn justify-center sm:justify-start">
                                See services
                            </a>
                        </div>

                        <div data-reveal data-delay="300" class="reveal-up mt-10 grid gap-4 sm:grid-cols-3">
                            @foreach ($heroStats as $stat)
                                <div class="marketing-stat-card">
                                    <p class="text-xs uppercase tracking-[0.2em] text-zinc-500">{{ $stat['label'] }}</p>
                                    <p class="mt-3 text-lg font-semibold text-zinc-950">{{ $stat['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div data-reveal data-delay="180" class="reveal-up">
                        <div class="marketing-hero-frame">
                            <div class="rounded-[28px] border border-black/10 bg-white p-3 shadow-[0_25px_70px_rgba(0,0,0,0.09)]">
                                <img
                                    src="{{ asset('images/packed-products.svg') }}"
                                    alt="Packed ecommerce products arranged in monochrome shipping boxes"
                                    class="h-auto w-full rounded-[22px]"
                                >
                            </div>

                            <div class="marketing-floating-card left-[-0.75rem] top-[8%]">
                                <p class="text-xs uppercase tracking-[0.2em] text-zinc-500">Visual focus</p>
                                <p class="mt-3 text-lg font-semibold text-zinc-950">Packed products</p>
                                <p class="mt-2 text-sm leading-6 text-zinc-600">Show the packaging, labels, and box quality immediately.</p>
                            </div>

                            <div class="marketing-floating-image">
                                <img
                                    src="{{ asset('images/logistics-fulfillment.svg') }}"
                                    alt="Logistics and fulfillment workflow illustration for ecommerce operations"
                                    class="h-auto w-full rounded-[20px]"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="px-4 pb-8 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    <div data-reveal data-delay="0" class="reveal-up glass-panel rounded-[34px] px-6 py-6 sm:px-8">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                            <div class="max-w-3xl">
                                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-zinc-500">Built for product and operations</p>
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

            <section id="solutions" class="px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
                <div class="mx-auto max-w-7xl">
                    <div data-reveal data-delay="0" class="reveal-up max-w-3xl">
                        <span class="marketing-badge">Core solutions</span>
                        <h2 class="marketing-display mt-6 text-4xl leading-[0.95] tracking-[-0.04em] text-zinc-950 sm:text-5xl">
                            Ecommerce on the front. Logistics and workflow support underneath.
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
                                <span class="marketing-tag">Packed product angle</span>
                            </div>

                            <div class="mt-7 overflow-hidden rounded-[28px] border border-black/10 bg-white">
                                <img
                                    src="{{ asset('images/packed-products.svg') }}"
                                    alt="Monochrome packaged products and ecommerce box image"
                                    class="h-auto w-full"
                                >
                            </div>

                            <ul class="mt-7 grid gap-4 text-sm leading-7 text-zinc-600">
                                @foreach ($ecommerceBullets as $bullet)
                                    <li class="flex items-start gap-3">
                                        <span class="mt-2 h-2.5 w-2.5 rounded-full bg-zinc-950"></span>
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
                                <span class="marketing-tag">Logistics angle</span>
                            </div>

                            <div class="mt-7 overflow-hidden rounded-[28px] border border-black/10 bg-white">
                                <img
                                    src="{{ asset('images/logistics-fulfillment.svg') }}"
                                    alt="Logistics, warehouse, and order flow image for a custom web application"
                                    class="h-auto w-full"
                                >
                            </div>

                            <ul class="mt-7 grid gap-4 text-sm leading-7 text-zinc-600">
                                @foreach ($webAppBullets as $bullet)
                                    <li class="flex items-start gap-3">
                                        <span class="mt-2 h-2.5 w-2.5 rounded-full bg-zinc-950"></span>
                                        <span>{{ $bullet }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </article>
                    </div>
                </div>
            </section>

            <section id="visuals" class="px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
                <div class="mx-auto max-w-7xl">
                    <div data-reveal data-delay="0" class="reveal-up flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-3xl">
                            <span class="marketing-badge">Visual system</span>
                            <h2 class="marketing-display mt-6 text-4xl leading-[0.95] tracking-[-0.04em] text-zinc-950 sm:text-5xl">
                                More product boxes, more visual proof, and a cleaner monochrome layout.
                            </h2>
                        </div>
                        <p class="max-w-xl text-base leading-7 text-zinc-600 sm:text-lg">
                            The light theme keeps everything readable, while the box-based composition helps ecommerce, packaging, and shipping businesses feel organized instead of noisy.
                        </p>
                    </div>

                    <div class="mt-10 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                        @foreach ($serviceCards as $card)
                            <article data-reveal data-delay="{{ $loop->index * 80 }}" class="reveal-up marketing-service-box">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-black/10 bg-zinc-950 text-white">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 12 3l9 4.5M3 7.5V16.5L12 21m-9-13.5 9 4.5m9-4.5V16.5L12 21m0-9v9" />
                                    </svg>
                                </div>
                                <h3 class="mt-5 text-2xl font-semibold tracking-[-0.03em] text-zinc-950">{{ $card['title'] }}</h3>
                                <p class="mt-4 text-base leading-7 text-zinc-600">{{ $card['copy'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="process" class="px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
                <div class="mx-auto grid max-w-7xl gap-8 lg:grid-cols-[0.88fr_1.12fr]">
                    <div data-reveal data-delay="0" class="reveal-up">
                        <span class="marketing-badge">Delivery process</span>
                        <h2 class="marketing-display mt-6 text-4xl leading-[0.95] tracking-[-0.04em] text-zinc-950 sm:text-5xl">
                            The website and the workflow layer are designed together, not as separate ideas.
                        </h2>
                        <p class="mt-5 max-w-xl text-lg leading-8 text-zinc-600">
                            That is what makes the result look more intentional. The visuals, content, boxes, product presentation, and app structure all reinforce the same story.
                        </p>
                    </div>

                    <div class="space-y-4">
                        @foreach ($process as $step)
                            <article data-reveal data-delay="{{ 70 + ($loop->index * 90) }}" class="reveal-up glass-panel rounded-[30px] p-6 sm:p-7">
                                <div class="flex flex-col gap-5 sm:flex-row">
                                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-black/10 bg-white text-lg font-bold text-zinc-950">
                                        {{ $step['number'] }}
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-semibold tracking-[-0.03em] text-zinc-950">{{ $step['title'] }}</h3>
                                        <p class="mt-3 text-base leading-7 text-zinc-600">{{ $step['copy'] }}</p>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="contact" class="px-4 pb-24 pt-8 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    <div data-reveal data-delay="0" class="reveal-up glass-panel-strong rounded-[38px] px-6 py-8 sm:px-8 lg:px-10">
                        <div class="grid gap-8 lg:grid-cols-[1fr_340px] lg:items-center">
                            <div>
                                <span class="marketing-badge">Ready to build</span>
                                <h2 class="marketing-display mt-6 text-4xl leading-[0.95] tracking-[-0.04em] text-zinc-950 sm:text-5xl">
                                    Need a lighter black-and-white website with real product and logistics visuals?
                                </h2>
                                <p class="mt-5 max-w-3xl text-lg leading-8 text-zinc-600">
                                    This direction is now set up for ecommerce development, packed product storytelling, and any type of custom web application that needs a clean modern surface.
                                </p>
                            </div>

                            <div class="rounded-[32px] border border-black/10 bg-white p-6 shadow-[0_22px_60px_rgba(0,0,0,0.08)]">
                                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-zinc-500">Next step</p>
                                <p class="mt-4 text-base leading-7 text-zinc-600">
                                    Share your project type, product category, and whether you also need admin, shipping, or client portal features.
                                </p>

                                @if ($hasDirectEmail)
                                    <a href="{{ $contactHref }}" class="marketing-primary-btn mt-6 w-full justify-center">
                                        Send project brief
                                    </a>
                                    <p class="mt-3 text-center text-xs text-zinc-500">{{ $contactEmail }}</p>
                                @else
                                    <a href="{{ route('login') }}" class="marketing-primary-btn mt-6 w-full justify-center">
                                        Request private demo
                                    </a>
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
    </body>
</html>
