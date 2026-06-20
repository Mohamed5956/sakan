@extends('layouts.app')

@section('title', 'كل العقارات | سكن')
@section('meta_description', 'تصفح كل العقارات المتاحة من شقق وغرف واستوديوهات بأفضل الأسعار')

@section('content')

    {{-- Page Header --}}
    <div class="bg-navy text-white py-10">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-3xl font-black mb-1">🏠 كل العقارات</h1>
            <p class="text-navy-200">
                {{ $properties->total() }} عقار متاح
                @if(array_filter($filters))
                    — نتائج البحث
                @endif
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">

            {{-- ════════════════════════════════════ --}}
            {{-- SIDEBAR SEARCH --}}
            {{-- ════════════════════════════════════ --}}
            <aside class="w-full lg:w-72 shrink-0">
                <div class="bg-white rounded-2xl shadow-md p-6 sticky top-24">
                    <h2 class="font-black text-navy text-xl mb-5 flex items-center gap-2">
                        🔍 تصفية النتائج
                    </h2>

                    <form action="{{ route('properties.index') }}" method="GET" id="search-form">

                        {{-- Keyword --}}
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">بحث بالكلمة</label>
                            <input
                                type="text"
                                name="keyword"
                                class="search-input"
                                placeholder="اسم أو عنوان..."
                                value="{{ $filters['keyword'] ?? '' }}"
                            >
                        </div>

                        {{-- Type --}}
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">نوع العقار</label>
                            <select name="type" class="search-input">
                                <option value="">الكل</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}"
                                        {{ ($filters['type'] ?? '') === $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- City --}}
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">المدينة</label>
                            <select name="city" class="search-input">
                                <option value="">الكل</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}"
                                        {{ ($filters['city'] ?? '') === $city ? 'selected' : '' }}>
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- District --}}
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">الحي / المنطقة</label>
                            <input
                                type="text"
                                name="district"
                                class="search-input"
                                placeholder="اسم الحي..."
                                value="{{ $filters['district'] ?? '' }}"
                            >
                        </div>

                        {{-- University --}}
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">أقرب جامعة</label>
                            <select name="university" class="search-input">
                                <option value="">الكل</option>
                                @foreach($universities as $uni)
                                    <option value="{{ $uni }}"
                                        {{ ($filters['university'] ?? '') === $uni ? 'selected' : '' }}>
                                        {{ $uni }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Price Range --}}
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">نطاق السعر (ج.م)</label>
                            <div class="flex gap-2">
                                <input
                                    type="number"
                                    name="min_price"
                                    class="search-input"
                                    placeholder="من"
                                    value="{{ $filters['min_price'] ?? '' }}"
                                >
                                <input
                                    type="number"
                                    name="max_price"
                                    class="search-input"
                                    placeholder="إلى"
                                    value="{{ $filters['max_price'] ?? '' }}"
                                >
                            </div>
                        </div>

                        {{-- Bathrooms --}}
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-1">الحمامات (على الأقل)</label>
                            <select name="bathrooms" class="search-input">
                                <option value="">الكل</option>
                                @foreach([1,2,3,4] as $n)
                                    <option value="{{ $n }}"
                                        {{ ($filters['bathrooms'] ?? '') == $n ? 'selected' : '' }}>
                                        {{ $n }}+
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn-primary w-full">بحث</button>

                        @if(array_filter($filters))
                            <a href="{{ route('properties.index') }}"
                               class="block text-center text-sm text-gray-500 hover:text-navy mt-3 transition">
                                ✕ إلغاء الفلاتر
                            </a>
                        @endif

                    </form>
                </div>
            </aside>

            {{-- ════════════════════════════════════ --}}
            {{-- RESULTS --}}
            {{-- ════════════════════════════════════ --}}
            <div class="flex-1 min-w-0">

                @if($properties->count())

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($properties as $property)
                            @include('properties._card', ['property' => $property])
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-10 flex justify-center">
                        {{ $properties->links() }}
                    </div>

                @else
                    <div class="text-center py-24 text-gray-400">
                        <div class="text-6xl mb-4">🏚️</div>
                        <h3 class="text-xl font-bold text-gray-600 mb-2">لا توجد نتائج</h3>
                        <p class="text-sm">جرب تغيير معايير البحث</p>
                        <a href="{{ route('properties.index') }}" class="btn-primary inline-block mt-6">
                            عرض كل العقارات
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Auto-submit on select change
        document.querySelectorAll('#search-form select').forEach(select => {
            select.addEventListener('change', () => {
                document.getElementById('search-form').submit();
            });
        });
    </script>
@endpush
