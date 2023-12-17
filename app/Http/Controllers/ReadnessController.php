<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReadnessResource;
use Illuminate\Http\Request;

class ReadnessController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return new ReadnessResource([]);
    }
}
