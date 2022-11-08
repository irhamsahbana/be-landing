<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SignupController extends Controller
{
    public function index(Request $request)
    {
        $response = new Response();
        return $response->json(null, 'Logout success');
    }
}
