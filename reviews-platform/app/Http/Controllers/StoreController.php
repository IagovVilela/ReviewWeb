<?php

namespace App\Http\Controllers;

class StoreController extends Controller
{
    /**
     * Show the store page (material de coleta). Links to external store URL (e.g. Shopify).
     */
    public function index()
    {
        $storeUrl = config('store.url');
        return view('store.index', compact('storeUrl'));
    }
}
