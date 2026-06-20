{{-- Navbar --}}
<nav class="bg-navy shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="text-2xl font-black text-white tracking-wide">سكن</span>
                <span class="text-navy-200 text-sm font-medium">| عقارات</span>
            </a>

            {{-- Nav Links --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('home') }}"
                   class="text-white hover:text-navy-200 font-medium transition
                              {{ request()->routeIs('home') ? 'border-b-2 border-white pb-1' : '' }}">
                    الرئيسية
                </a>
                <a href="{{ route('properties.index') }}"
                   class="text-white hover:text-navy-200 font-medium transition
                              {{ request()->routeIs('properties.*') ? 'border-b-2 border-white pb-1' : '' }}">
                    العقارات
                </a>
            </div>

            {{-- Mobile menu button --}}
            <button id="mobile-menu-btn" class="md:hidden text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden pb-4">
            <a href="{{ route('home') }}"
               class="block text-white py-2 hover:text-navy-200 font-medium">الرئيسية</a>
            <a href="{{ route('properties.index') }}"
               class="block text-white py-2 hover:text-navy-200 font-medium">العقارات</a>
        </div>
    </div>
</nav>
