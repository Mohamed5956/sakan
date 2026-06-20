{{-- Footer --}}
<footer class="bg-navy text-white">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div>
                <h3 class="text-2xl font-black mb-3">سكن</h3>
                <p class="text-navy-200 text-sm leading-relaxed">
                    منصتك الموثوقة للبحث عن العقارات والشقق والغرف في مصر.
                </p>
            </div>

            <div>
                <h4 class="font-bold mb-3 text-lg">روابط سريعة</h4>
                <ul class="space-y-2 text-navy-200 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">الرئيسية</a></li>
                    <li><a href="{{ route('properties.index') }}" class="hover:text-white transition">كل العقارات</a></li>
                    <li>
                        <a href="{{ route('properties.index', ['type' => 'شقة']) }}"
                           class="hover:text-white transition">الشقق</a>
                    </li>
                    <li>
                        <a href="{{ route('properties.index', ['type' => 'غرفة']) }}"
                           class="hover:text-white transition">الغرف</a>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold mb-3 text-lg">تواصل معنا</h4>
                <p class="text-navy-200 text-sm">info@sakan.com</p>
            </div>

        </div>

        <div class="border-t border-navy-700 mt-8 pt-6 text-center text-navy-300 text-sm">
            © {{ date('Y') }} سكن — جميع الحقوق محفوظة
        </div>
    </div>
</footer>
