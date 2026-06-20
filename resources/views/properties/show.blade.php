@extends('layouts.app')

@section('title', $property->seo_title)
@section('meta_description', $property->seo_description)
@section('og_image', $property->main_image ? asset('storage/'.$property->main_image) : asset('images/property-placeholder.webp'))

@section('content')

    {{-- ═══════════════════════════════════════════ --}}
    {{-- SCHEMA.ORG STRUCTURED DATA --}}
    {{-- ═══════════════════════════════════════════ --}}
{{--    <script type="application/ld+json">--}}
{{--        {--}}
{{--            "@context": "https://schema.org",--}}
{{--            "@type": "RealEstateListing",--}}
{{--            "name": "{{ $property->title }}",--}}
{{--            "description": "{{ Str::limit(strip_tags($property->description), 200) }}",--}}
{{--            "url": "{{ route('properties.show', $property) }}",--}}
{{--            "image": "{{ $property->main_image ? asset('storage/'.$property->main_image) : asset('images/og-default.jpg') }}",--}}
{{--                @if($property->price)--}}
{{--                    "offers": {--}}
{{--                        "@type": "Offer",--}}
{{--                        "price": "{{ $property->price }}",--}}
{{--                "priceCurrency": "EGP"--}}
{{--            },--}}
{{--                @endif--}}
{{--                @if($property->latitude && $property->longitude)--}}
{{--                    "geo": {--}}
{{--                        "@type": "GeoCoordinates",--}}
{{--                        "latitude": "{{ $property->latitude }}",--}}
{{--                "longitude": "{{ $property->longitude }}"--}}
{{--            },--}}
{{--                @endif--}}
{{--                "address": {--}}
{{--                    "@type": "PostalAddress",--}}
{{--                    "streetAddress": "{{ $property->address }}",--}}
{{--                "addressLocality": "{{ $property->city }}",--}}
{{--                "addressCountry": "EG"--}}
{{--            }--}}
{{--        }--}}
{{--    </script>--}}

    {{-- ═══════════════════════════════════════════ --}}
    {{-- BREADCRUMB --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="bg-gray-100 border-b">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <nav class="flex items-center gap-2 text-sm text-gray-500" aria-label="breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-navy transition">الرئيسية</a>
                <span>/</span>
                <a href="{{ route('properties.index') }}" class="hover:text-navy transition">العقارات</a>
                <span>/</span>
                <span class="text-navy font-medium truncate max-w-xs">{{ $property->title }}</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">

            {{-- ════════════════════════════════════ --}}
            {{-- MAIN CONTENT --}}
            {{-- ════════════════════════════════════ --}}
            <div class="flex-1 min-w-0">

                {{-- ── Main Image + Gallery ──────── --}}
                <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-6">

                    {{-- Main Image --}}
                    <div class="relative">
                        <img
                            id="main-photo"
                            src="{{ $property->main_image ? asset('storage/'.$property->main_image) : asset('images/property-placeholder.webp') }}"
                            alt="{{ $property->title }}"
                            class="w-full h-80 md:h-[480px] object-cover"
                            itemprop="image"
                        >

                        {{-- Badges overlay --}}
                        <div class="absolute top-4 right-4 flex flex-col gap-2">
                            <span class="badge-navy badge">{{ $property->type }}</span>
                            @if($property->is_featured)
                                <span class="badge bg-yellow-400 text-yellow-900">⭐ مميز</span>
                            @endif
                        </div>

                        {{-- Views --}}
                        <div class="absolute bottom-4 left-4 bg-black/50 text-white text-xs px-3 py-1 rounded-full">
                            👁️ {{ number_format($property->views) }} مشاهدة
                        </div>
                    </div>

                    {{-- Gallery Thumbnails --}}
                    @if($property->images->count())
                        <div class="p-4 flex gap-3 overflow-x-auto">
                            {{-- Main image thumb --}}
                            @if($property->main_image)
                                <button
                                    onclick="switchPhoto('{{ asset('storage/'.$property->main_image) }}')"
                                    class="shrink-0 w-20 h-16 rounded-lg overflow-hidden border-2 border-navy focus:outline-none"
                                >
                                    <img src="{{ asset('storage/'.$property->main_image) }}"
                                         alt="صورة رئيسية"
                                         class="w-full h-full object-cover">
                                </button>
                            @endif

                            {{-- Gallery images --}}
                            @foreach($property->images as $img)
                                <button
                                    onclick="switchPhoto('{{ asset('storage/'.$img->image_path) }}')"
                                    class="shrink-0 w-20 h-16 rounded-lg overflow-hidden border-2 border-transparent
                               hover:border-navy transition focus:outline-none"
                                >
                                    <img src="{{ asset('storage/'.$img->image_path) }}"
                                         alt="{{ $img->alt ?: $property->title }}"
                                         class="w-full h-full object-cover"
                                         loading="lazy">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- ── Title & Basic Info ───────── --}}
                <div class="bg-white rounded-2xl shadow-md p-6 mb-6">

                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-black text-navy mb-2">
                                {{ $property->title }}
                            </h1>
                            <p class="text-gray-500 flex items-center gap-1">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $property->address }}
                                @if($property->city) — {{ $property->city }} @endif
                                @if($property->district) / {{ $property->district }} @endif
                            </p>
                        </div>

                        @if($property->price)
                            <div class="bg-navy-50 rounded-xl px-6 py-4 text-center shrink-0">
                                <div class="text-3xl font-black text-navy">
                                    {{ number_format($property->price) }}
                                    <span class="text-lg">ج.م</span>
                                </div>
                                <div class="text-sm text-gray-500">/ {{ $property->price_period }}</div>
                            </div>
                        @endif
                    </div>

                    {{-- Quick Stats --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t">
                        <div class="text-center p-3 bg-gray-50 rounded-xl">
                            <div class="text-2xl mb-1">🚪</div>
                            <div class="font-black text-navy text-xl">{{ $property->total_rooms }}</div>
                            <div class="text-xs text-gray-500">غرفة</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-xl">
                            <div class="text-2xl mb-1">🛏️</div>
                            <div class="font-black text-navy text-xl">{{ $property->total_beds }}</div>
                            <div class="text-xs text-gray-500">سرير</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-xl">
                            <div class="text-2xl mb-1">🚿</div>
                            <div class="font-black text-navy text-xl">{{ $property->bathrooms }}</div>
                            <div class="text-xs text-gray-500">حمام</div>
                        </div>
                        @if($property->nearest_university)
                            <div class="text-center p-3 bg-gray-50 rounded-xl">
                                <div class="text-2xl mb-1">🎓</div>
                                <div class="font-bold text-navy text-sm leading-tight">
                                    {{ $property->nearest_university }}
                                </div>
                                <div class="text-xs text-gray-500">أقرب جامعة</div>
                            </div>
                        @endif
                    </div>

                </div>

                {{-- ── Description ──────────────── --}}
                @if($property->description)
                    <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
                        <h2 class="text-xl font-black text-navy mb-4 flex items-center gap-2">
                            📋 وصف العقار
                        </h2>
                        <div class="text-gray-600 leading-relaxed whitespace-pre-line">
                            {{ $property->description }}
                        </div>
                    </div>
                @endif

                {{-- ── Rooms Detail ──────────────── --}}
                @if($property->rooms->count())
                    <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
                        <h2 class="text-xl font-black text-navy mb-4 flex items-center gap-2">
                            🚪 تفاصيل الغرف
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($property->rooms as $room)
                                <div class="flex items-center justify-between bg-gray-50 rounded-xl p-4 border border-gray-100">
                                    <div>
                                        <div class="font-bold text-navy">{{ $room->name }}</div>
                                        @if($room->notes)
                                            <div class="text-xs text-gray-500 mt-1">{{ $room->notes }}</div>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 bg-white rounded-lg px-3 py-2 shadow-sm border">
                                        <span class="text-xl">🛏️</span>
                                        <span class="font-black text-navy">{{ $room->beds }}</span>
                                        <span class="text-xs text-gray-500">سرير</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ── Google Map ────────────────── --}}
                @if($property->latitude && $property->longitude)
                    <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
                        <h2 class="text-xl font-black text-navy mb-4 flex items-center gap-2">
                            📍 الموقع على الخريطة
                        </h2>
                        <div class="rounded-xl overflow-hidden border border-gray-200" style="height: 380px;">
                            <iframe
                                width="100%"
                                height="100%"
                                style="border:0"
                                loading="lazy"
                                allowfullscreen
                                referrerpolicy="no-referrer-when-downgrade"
                                src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google_maps.key') }}&q={{ $property->latitude }},{{ $property->longitude }}&zoom=16&language=ar"
                            ></iframe>
                        </div>

                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $property->latitude }},{{ $property->longitude }}"
                        target="_blank"
                        rel="noopener"
                        class="inline-flex items-center gap-2 mt-3 text-sm text-navy hover:text-navy-700 font-medium transition"
                        >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        الحصول على الاتجاهات في Google Maps
                        </a>
                    </div>
                @endif

            </div>

            {{-- ════════════════════════════════════ --}}
            {{-- SIDEBAR --}}
            {{-- ════════════════════════════════════ --}}
            <aside class="w-full lg:w-80 shrink-0">

                {{-- Contact Card --}}
                <div class="bg-white rounded-2xl shadow-md p-6 mb-6 sticky top-24">
                    <h2 class="text-lg font-black text-navy mb-5 text-center">
                        📞 تواصل مع صاحب العقار
                    </h2>

                    <div class="flex flex-col gap-3">

                        @if($property->whatsapp)

                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $property->whatsapp) }}"
                            target="_blank"
                            rel="noopener"
                            class="flex items-center justify-center gap-3 bg-green-500 hover:bg-green-600
                            text-white font-bold py-3 px-4 rounded-xl transition-all
                            shadow hover:shadow-md active:scale-95"
                            >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            واتساب
                            </a>
                        @endif

                        @if($property->phone)

                            <a href="tel:{{ $property->phone }}"
                            class="flex items-center justify-center gap-3 bg-navy hover:bg-navy-800
                            text-white font-bold py-3 px-4 rounded-xl transition-all
                            shadow hover:shadow-md active:scale-95"
                            >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/>
                            </svg>
                            {{ $property->phone }}
                            </a>
                        @endif

                        @if($property->email)

                            <a href="mailto:{{ $property->email }}"
                            class="flex items-center justify-center gap-3 bg-gray-100 hover:bg-gray-200
                            text-gray-700 font-bold py-3 px-4 rounded-xl transition-all
                            shadow hover:shadow-md active:scale-95"
                            >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $property->email }}
                            </a>
                        @endif

                        @if(!$property->whatsapp && !$property->phone && !$property->email)
                            <p class="text-center text-gray-400 text-sm py-4">
                                لا توجد بيانات تواصل متاحة
                            </p>
                        @endif

                    </div>

                    {{-- Share --}}
                    <div class="mt-5 pt-5 border-t">
                        <p class="text-sm font-bold text-gray-600 mb-3 text-center">مشاركة العقار</p>
                        <div class="flex justify-center gap-3">

                            {{-- WhatsApp Share --}}
                            <a href="https://wa.me/?text={{ urlencode($property->title . ' - ' . route('properties.show', $property)) }}"
                               target="_blank" rel="noopener"
                               class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </a>

                            {{-- Copy Link --}}
                            <button
                                onclick="copyLink()"
                                class="p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition"
                                title="نسخ الرابط"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>

                        </div>
                    </div>
                </div>

            </aside>
        </div>

        {{-- ════════════════════════════════════════ --}}
        {{-- RELATED PROPERTIES --}}
        {{-- ════════════════════════════════════════ --}}
        @if($related->count())
            <section class="mt-12">
                <h2 class="section-title mb-2">🏠 عقارات مشابهة</h2>
                <p class="section-subtitle">قد تعجبك أيضاً</p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
                    @foreach($related as $prop)
                        @include('properties._card', ['property' => $prop])
                    @endforeach
                </div>
            </section>
        @endif

    </div>

@endsection

@push('scripts')
    <script>
        // Gallery switcher
        function switchPhoto(src) {
            const main = document.getElementById('main-photo');
            main.style.opacity = '0';
            setTimeout(() => {
                main.src = src;
                main.style.opacity = '1';
            }, 150);
            main.style.transition = 'opacity 0.15s ease';
        }

        // Copy link
        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const btn = event.currentTarget;
                btn.classList.add('bg-green-100', 'text-green-600');
                setTimeout(() => btn.classList.remove('bg-green-100', 'text-green-600'), 2000);
            });
        }
    </script>
@endpush
