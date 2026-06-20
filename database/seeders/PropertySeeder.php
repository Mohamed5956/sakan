<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Room;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        Property::factory(30)->create()->each(function (Property $property) {

            $roomCount = match($property->type) {
                'غرفة'       => 1,
                'استوديو'    => 1,
                'شقة'        => rand(2, 3),
                'دوبلكس'     => rand(3, 4),
                'فيلا'       => rand(3, 5),
                default      => rand(1, 3),
            };

            Room::factory($roomCount)->create([
                'property_id' => $property->id,
            ]);
        });

        $this->command->info('✅ تم إنشاء 30 عقار مع غرفهم بنجاح');
    }
}
