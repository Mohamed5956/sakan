<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only([
            'keyword',
            'type',
            'city',
            'district',
            'university',
            'min_price',
            'max_price',
            'bathrooms',
        ]);

        $properties = Property::active()
            ->with(['images'])
            ->search($filters)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $types        = Property::active()->distinct()->pluck('type');
        $cities       = Property::active()->distinct()->pluck('city')->filter();
        $universities = Property::active()->distinct()->pluck('nearest_university')->filter();

        return view('properties.index', compact(
            'properties',
            'filters',
            'types',
            'cities',
            'universities'
        ));
    }

    public function show(Property $property)
    {
        abort_if(!$property->is_active, 404);

        $property->incrementViews();
        $property->load(['rooms', 'images']);

        // Related properties same city or type
        $related = Property::active()
            ->where('id', '!=', $property->id)
            ->where(function ($q) use ($property) {
                $q->where('city', $property->city)
                    ->orWhere('type', $property->type);
            })
            ->with(['images'])
            ->latest()
            ->take(4)
            ->get();

        return view('properties.show', compact('property', 'related'));
    }
}
