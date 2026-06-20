<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    private array $roomNames = [
        'غرفة النوم الرئيسية',
        'غرفة النوم الثانية',
        'غرفة النوم الثالثة',
        'غرفة الأطفال',
        'الغرفة الخارجية',
    ];

    public function definition(): array
    {
        return [
            'name'  => $this->faker->randomElement($this->roomNames),
            'beds'  => $this->faker->numberBetween(1, 3),
            'notes' => $this->faker->boolean(30)
                ? $this->faker->randomElement([
                    'تكييف مركزي',
                    'دولاب مدمج',
                    'حمام داخلي',
                    'إطلالة على الحديقة',
                    'نافذة كبيرة',
                ])
                : null,
        ];
    }
}
