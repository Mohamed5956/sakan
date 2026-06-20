<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    private array $arabicTitles = [
        'شقة مفروشة بالكامل قريبة من الجامعة',
        'غرفة مريحة في موقع مميز',
        'استوديو حديث للإيجار',
        'شقة واسعة بإطلالة رائعة',
        'غرفة هادئة في حي راقي',
        'شقة مجددة بالكامل قرب المواصلات',
        'وحدة سكنية فاخرة بمدخل مستقل',
        'شقة للطلاب قريبة من الكليات',
        'دوبلكس أنيق في منطقة هادئة',
        'فيلا مجهزة بحديقة خاصة',
        'غرفة مفروشة بحمام خاص',
        'شقة بالدور الأرضي مع حديقة',
        'استوديو مؤثث بالكامل للإيجار الشهري',
        'شقة عائلية واسعة في موقع مركزي',
        'غرفة طلابية بأسعار مناسبة',
    ];

    private array $cities = [
        'القاهرة', 'الجيزة', 'الإسكندرية',
        'أسيوط', 'المنصورة', 'طنطا',
        'سوهاج', 'قنا', 'الفيوم', 'المنيا',
    ];

    private array $districts = [
        'المعادي', 'مدينة نصر', 'الزمالك', 'العباسية',
        'الدقي', 'المهندسين', 'الهرم', 'شبرا',
        'السيدة زينب', 'بولاق', 'روض الفرج',
        'أرض اللواء', 'الحي الثامن', 'الحي العاشر',
    ];

    private array $universities = [
        'جامعة القاهرة',
        'جامعة عين شمس',
        'جامعة الأزهر',
        'جامعة أسيوط',
        'جامعة المنصورة',
        'جامعة الإسكندرية',
        'جامعة طنطا',
        'جامعة سوهاج',
        'جامعة المنيا',
        'جامعة الفيوم',
    ];

    private array $types = [
        'شقة', 'غرفة', 'استوديو',
        'فيلا', 'دوبلكس', 'وحدة سكنية',
    ];

    private array $descriptions = [
        'شقة مريحة ومجهزة بالكامل، تقع في موقع متميز قريب من الخدمات والمواصلات. مناسبة للعائلات والطلاب.',
        'وحدة سكنية هادئة بعيدة عن الضوضاء، تتميز بالتهوية الجيدة والإضاءة الطبيعية الوفيرة.',
        'سكن طلابي مثالي بقرب من الجامعة والمحلات التجارية، متاح للإيجار الشهري أو الفصلي.',
        'شقة حديثة التشطيب بموقع استراتيجي على الطريق الرئيسي، سهلة الوصول لجميع الخدمات.',
        'وحدة فاخرة بتشطيبات عالية الجودة، مناسبة للعائلات والأزواج الجدد.',
        'غرفة مستقلة بمدخل خاص وحمام داخلي، هادئة ومناسبة للطلاب والموظفين.',
        'شقة بالقرب من المستشفيات والمدارس والمواصلات، متكاملة الخدمات.',
        'وحدة سكنية واسعة تتكون من عدة غرف، مثالية للعائلات الكبيرة.',
    ];

    private function generateUniqueSlug(string $title): string
    {
        $slug = preg_replace('/\s+/u', '-', trim($title));
        $slug = preg_replace('/[^\p{Arabic}\p{N}-]/u', '', $slug);
        $slug = preg_replace('/-+/', '-', $slug);

        $random = substr(str_shuffle('0123456789'), 0, 4);

        $finalSlug = $slug . '-' . $random;

        $counter = 1;
        $original = $slug;

        while (Property::where('slug', $finalSlug)->exists() && $counter < 50) {
            $finalSlug = $original . '-' . $random . '-' . $counter;
            $counter++;
        }

        return $finalSlug;
    }

    public function definition(): array
    {
        $city     = $this->faker->randomElement($this->cities);
        $type     = $this->faker->randomElement($this->types);
        $title    = $this->faker->randomElement($this->arabicTitles);
        $price    = $this->faker->randomElement([
            500, 700, 800, 1000, 1200, 1500,
            2000, 2500, 3000, 3500, 4000, 5000,
        ]);
        return [
            'title'              => $title,
            'slug'               => $this->generateUniqueSlug($title),
            'description'        => $this->faker->randomElement($this->descriptions),
            'type'               => $type,
            'price'              => $price,
            'price_period'       => $this->faker->randomElement(['شهري', 'سنوي']),
            'city'               => $city,
            'district'           => $this->faker->randomElement($this->districts),
            'address'            => $this->faker->randomElement($this->districts) . '، ' . $city . '، مصر',
            'nearest_university' => $this->faker->randomElement($this->universities),
            'bathrooms'          => $this->faker->numberBetween(1, 3),
            'main_image'         => null,
            'whatsapp'           => '201' . $this->faker->numerify('#########'),
            'phone'              => '01' . $this->faker->randomElement(['0','1','2','5']) . $this->faker->numerify('########'),
            'email'              => $this->faker->safeEmail(),
            'meta_title'         => $title . ' | سكن',
            'meta_description'   => 'إيجار ' . $type . ' في ' . $city . ' — ' . number_format($price) . ' ج.م شهرياً. تواصل معنا الآن.',
            'is_active'          => true,
            'is_featured'        => $this->faker->boolean(20),
            'views'              => $this->faker->numberBetween(0, 500),
            'latitude'           => $this->faker->latitude(22, 31),
            'longitude'          => $this->faker->longitude(25, 35),
        ];
    }
}
