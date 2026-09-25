<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Support\Collection;

class BrandContext
{
    public function brands(): Collection
    {
        return Brand::orderBy('name')->get();
    }

    public function current(): ?Brand
    {
        $brands = $this->brands();
        $id = request('brand') ?? session('brand_id');
        $brand = $id ? $brands->firstWhere('id', (int) $id) : null;
        $brand ??= $brands->first();
        if ($brand) {
            session(['brand_id' => $brand->id]);
        }
        return $brand;
    }
}
