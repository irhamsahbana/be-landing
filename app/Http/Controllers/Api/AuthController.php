<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Libs\Response;
use App\Models\Signup;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function attempt(Request $request)
    {
        $fields = [
            'username' => $request->username,
            'password' => $request->password
        ];

        $rules = [
            'username' => ['required'],
            'password' => ['required']
        ];

        $validator = Validator::make($fields, $rules);
        $response = new Response();
        if ($validator->fails())
            return $response->json(null, $validator->errors(), HttpResponse::HTTP_UNPROCESSABLE_ENTITY);


       if (Auth::attempt($fields)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return$response->json([
                'token' => $token,
                'user' => $user,
            ], 'Login success');
        }

        return $response->json(null, 'Invalid login credentials.', HttpResponse::HTTP_UNAUTHORIZED);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        $response = new Response();
        return $response->json(null, 'Logout success');
    }

    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        $response = new Response();
        return $response->json(null, 'Logout all success');
    }

    public function signup(Request $request)
    {
        $fields = $request->all();

        $rules = [
            'is_mentor' => ['required', 'boolean'],
            'industry_id' => [
                'required',
                'uuid',
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->where('group_by', 'industries');
                })
            ],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc'],
            'country_code' => [
                'nullable',
                'integer',
                'between:1,999'
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20'
            ],
            'message' => ['nullable', 'string', 'max:150'],
        ];

        $validator = Validator::make($fields, $rules);
        $response = new Response();
        if ($validator->fails())
            return $response->json(null, $validator->errors(), HttpResponse::HTTP_UNPROCESSABLE_ENTITY);

        $signup = Signup::create($fields);


        return $response->json($signup->toArray(), 'Signup success');
    }
}
