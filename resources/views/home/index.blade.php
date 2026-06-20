@extends('layouts.app')

@section('title', 'سكن | ابحث عن عقارك المثالي')
@section('meta_description', 'سكن - منصة البحث عن العقارات والشقق والغرف السكنية في مصر بأفضل الأسعار')

@section('content')

    {{-- ═══════════════════════════════════════════ --}}
    {{-- HERO --}}
    {{-- ═══════════════════════════════════════════ --}}
    <section class="relative bg-navy overflow-hidden" style="min-height: 580px;">

        {{-- Background pattern --}}
        <div class="absolute inset-0 opacity-10">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)"/>
            </svg>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 py-24 text-center">

        <span class="badge bg-white/20 text-white mb-4 inline-block text-sm px-4 py-1.5 rounded-full">
            🏠 {{ number_format($totalCount) }}+ عقار متاح
        </span>

            <h1 class="text-4xl md:text-6xl font-black text-white mb-4 leading-tight">
                ابحث عن
                <span class="text-navy-200">سكنك المثالي</span>
            </h1>

            <p class="text-navy-200 text-lg md:text-xl mb-10 max-w-2xl mx-auto">
                آلاف العقارات من شقق وغرف واستوديوهات في أفضل المناطق
            </p>

            {{-- Quick Search Box --}}
            <div class="bg-white rounded-2xl shadow-2xl p-4 max-w-4xl mx-auto">
                <form action="{{ route('properties.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                        <input
                            type="text"
                            name="keyword"
                            placeholder="🔍 ابحث عن عقار..."
                            class="search-input md:col-span-2"
                            value="{{ request('keyword') }}"
                        >

                        <select name="type" class="search-input">
                            <option value="">كل الأنواع</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>

                        <select name="city" class="search-input">
                            <option value="">كل المدن</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}">{{ $city }}</option>
                            @endforeach
                        </select>

                    </div>
                    <button type="submit" class="btn-primary w-full mt-3 text-lg">
                        بحث عن العقارات
                    </button>
                </form>
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- STATS BAR --}}
    {{-- ═══════════════════════════════════════════ --}}
    <section class="bg-navy-800 text-white py-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div>
                    <div class="text-2xl font-black text-navy-200">{{ number_format($totalCount) }}+</div>
                    <div class="text-sm text-gray-300">عقار مسجل</div>
                </div>
                <div>
                    <div class="text-2xl font-black text-navy-200">{{ $cities->count() }}+</div>
                    <div class="text-sm text-gray-300">مدينة</div>
                </div>
                <div>
                    <div class="text-2xl font-black text-navy-200">{{ $types->count() }}</div>
                    <div class="text-sm text-gray-300">نوع عقار</div>
                </div>
                <div>
                    <div class="text-2xl font-black text-navy-200">100%</div>
                    <div class="text-sm text-gray-300">موثوق</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- FEATURED PROPERTIES --}}
    {{-- ═══════════════════════════════════════════ --}}
    @if($featured->count())
        <section class="py-16 max-w-7xl mx-auto px-4">

            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="section-title">⭐ العقارات المميزة</h2>
                    <p class="section-subtitle">عقارات مختارة بعناية لك</p>
                </div>
                <a href="{{ route('properties.index', ['featured' => 1]) }}"
                   class="btn-outline hidden md:inline-block text-sm">
                    عرض الكل
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featured as $property)
                    @include('properties._card', ['property' => $property])
                @endforeach
            </div>

        </section>
    @endif

    {{-- ═══════════════════════════════════════════ --}}
    {{-- BROWSE BY TYPE --}}
    {{-- ═══════════════════════════════════════════ --}}
    <section class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4">

            <h2 class="section-title text-center">تصفح حسب النوع</h2>
            <p class="section-subtitle text-center">اختر ما يناسبك</p>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mt-8">
                @php
                    $typeIcons = [
                        'شقة'        => '🏢',
                        'غرفة'       => '🛏️',
                        'استوديو'    => '🏠',
                        'فيلا'       => '🏡',
                        'دوبلكس'     => '🏘️',
                        'وحدة سكنية' => '🏗️',
                    ];
                @endphp

                @foreach($types as $type)
                    <a href="{{ route('properties.index', ['type' => $type]) }}"
                       class="bg-white rounded-2xl p-5 text-center shadow hover:shadow-lg
                      hover:-translate-y-1 transition-all duration-200 group">
                        <div class="text-3xl mb-2">{{ $typeIcons[$type] ?? '🏠' }}</div>
                        <div class="font-bold text-navy group-hover:text-navy-500 text-sm">{{ $type }}</div>
                    </a>
                @endforeach
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- LATEST PROPERTIES --}}
    {{-- ═══════════════════════════════════════════ --}}
    @if($latest->count())
        <section class="py-16 max-w-7xl mx-auto px-4">

            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="section-title">🆕 أحدث العقارات</h2>
                    <p class="section-subtitle">عقارات أضيفت مؤخراً</p>
                </div>
                <a href="{{ route('properties.index') }}" class="btn-outline hidden md:inline-block text-sm">
                    عرض الكل
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($latest as $property)
                    @include('properties._card', ['property' => $property])
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('properties.index') }}" class="btn-primary inline-block">
                    عرض كل العقارات
                </a>
            </div>

        </section>
    @endif

    {{-- ═══════════════════════════════════════════ --}}
    {{-- CTA SECTION --}}
    {{-- ═══════════════════════════════════════════ --}}
    <section class="bg-navy py-16 text-center text-white">
        <div class="max-w-3xl mx-auto px-4">
            <h2 class="text-3xl font-black mb-4">لديك عقار للإيجار؟</h2>
            <p class="text-navy-200 mb-8 text-lg">
                تواصل معنا لإضافة عقارك وعرضه لآلاف الباحثين عن السكن
            </p>
            <a href="mailto:info@sakan.com" class="bg-white text-navy px-8 py-4 rounded-xl font-black
               hover:bg-navy-50 transition text-lg shadow-lg">
                تواصل معنا الآن
            </a>
        </div>
    </section>

@endsection
