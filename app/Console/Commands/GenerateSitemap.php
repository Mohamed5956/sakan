<?php

namespace App\Console\Commands;

use App\Models\Property;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature   = 'sitemap:generate';
    protected $description = 'توليد خريطة الموقع تلقائياً';

    public function handle(): void
    {
        $sitemap = Sitemap::create();

        // Static pages
        $sitemap->add(
            Url::create(route('home'))
                ->setPriority(1.0)
                ->setChangeFrequency('daily')
        );

        $sitemap->add(
            Url::create(route('properties.index'))
                ->setPriority(0.9)
                ->setChangeFrequency('hourly')
        );

        // Dynamic property pages
        Property::active()
            ->latest()
            ->each(function (Property $property) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('properties.show', $property))
                        ->setLastModificationDate($property->updated_at)
                        ->setPriority(0.8)
                        ->setChangeFrequency('weekly')
                );
            });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('✅ تم توليد sitemap.xml بنجاح — ' . Property::active()->count() . ' عقار');
    }
}
