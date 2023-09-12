<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class SwaggerController extends Controller
{
    public function swagger(Request $request) : View
    {
        return view('swagger');
    }

    public function swaggeryml(Request $request)
    {
        return response()->file(public_path('api.yaml'));
    }
}
