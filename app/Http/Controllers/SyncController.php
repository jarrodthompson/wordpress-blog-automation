<?php

namespace App\Http\Controllers;

use App\Services\BrandContext;
use App\Services\WordPressService;

class SyncController extends Controller
{
    public function sync(BrandContext $ctx)
    {
        $brand = $ctx->current();
        abort_if(! $brand, 404);

        $service = new WordPressService($brand);
        $service->syncSnapshot();

        return back()->with('status', 'Synced live data from '.$brand->domain);
    }
}
