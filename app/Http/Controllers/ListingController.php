<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index() {
        $listing    = Listing::withCount('transaction')->orderBy('transaction_count', 'desc')->paginate();
        return json_encode([
            'success'   => true,
            'message'   => 'List data listing',
            'data'      => $listing
        ]);
    }

    public function show(Listing $listing) {
        return json_encode([
            'success'   => true,
            'message'   => 'Detail data listing',
            'data'      => $listing
        ]);
    }
}
