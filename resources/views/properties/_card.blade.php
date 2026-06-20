<article class="property-card group" itemscope itemtype="https://schema.org/RealEstateListing">

    {{-- Image --}}
    <a href="{{ route('properties.show', $property) }}" class="block overflow-hidden relative">
        <img
            src="{{ $property->main_image
        ? asset('storage/' . $property->main_image)
        : asset('images/property-placeholder.webp') }}"
            alt="{{ $property->title }}"
            class="w-full h-52 object-cover group-hover:scale-105 transition-transform duration-300"
            loading="lazy"
            itemprop="image"
        >

        {{-- Badges --}}
        <div class="absolute top-3 right-3 flex flex-col gap-1">
            <span class="badge-navy badge text-xs">{{ $property->type }}</span>
            @if($property->is_featured)
                <span class="badge bg-yellow-400 text-yellow-900 text-xs">⭐ مميز</span>
            @endif
        </div>

        {{-- Price --}}
        @if($property->price)
            <div class="absolute bottom-3 left-3 bg-navy/90 text-white px-3 py-1 rounded-lg text-sm font-bold">
                {{ number_format($property->price) }} ج.م
                <span class="text-navy-200 text-xs">/{{ $property->price_period }}</span>
            </div>
        @endif
    </a>

    {{-- Content --}}
    <div class="p-4">
        <h3 class="font-bold text-navy text-lg mb-1 line-clamp-1" itemprop="name">
            <a href="{{ route('properties.show', $property) }}" class="hover:text-navy-500 transition">
                {{ $property->title }}
            </a>
        </h3>

        <p class="text-gray-500 text-sm mb-3 flex items-center gap-1">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            {{ $property->city }}{{ $property->district ? ' - ' . $property->district : '' }}
        </p>

        {{-- Stats --}}
        <div class="flex items-center gap-4 text-sm text-gray-600 border-t pt-3">
            @if($property->total_rooms > 0)
                <span class="flex items-center gap-1">
                🚪 {{ $property->total_rooms }} غرفة
            </span>
            @endif
            @if($property->total_beds > 0)
                <span class="flex items-center gap-1">
                🛏️ {{ $property->total_beds }} سرير
            </span>
            @endif
            <span class="flex items-center gap-1">
                🚿 {{ $property->bathrooms }}
            </span>
            <span class="flex items-center gap-1 mr-auto text-xs text-gray-400">
                👁️ {{ number_format($property->views) }}
            </span>
        </div>
    </div>

</article>
