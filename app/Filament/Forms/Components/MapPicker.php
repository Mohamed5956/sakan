<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;
use Closure;

class MapPicker extends Field
{
    protected string $view = 'filament.components.map-picker';

    protected string|Closure|null $latField = 'latitude';
    protected string|Closure|null $lngField = 'longitude';

    public function latField(string|Closure $field): static
    {
        $this->latField = $field;
        return $this;
    }

    public function lngField(string|Closure $field): static
    {
        $this->lngField = $field;
        return $this;
    }

    public function getLatField(): ?string
    {
        return $this->evaluate($this->latField);
    }

    public function getLngField(): ?string
    {
        return $this->evaluate($this->lngField);
    }

    public function getLat(): ?string
    {
        return $this->getRecord()?->latitude;
    }

    public function getLng(): ?string
    {
        return $this->getRecord()?->longitude;
    }
}
