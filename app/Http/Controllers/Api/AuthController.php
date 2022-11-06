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
                'nullable',
                'uuid',
                Rule::requiredIf(fn () => $request->is_mentor),
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->where('group_by', 'industries');
                })
            ],
            'first_name' => ['required_with:last_name', 'string', 'max:255'],
            'last_name' => ['required_with:first_name', 'string', 'max:255'],
            'email' => ['required', 'email:rfc,dns'],
            'country_code' => [
                'nullable',
                'integer',
                'between:1,999',
                'required_with:phone',
            ],
            'phone' => [
                'nullable',
                'integer',
                Rule::requiredIf(fn () => $request->is_mentor),
            ],
            'message' => ['nullable', 'string', 'max:150'],
        ];

        $messages = [
            'industry_id.required_if' => 'Please choose one industry.',

            'first_name.required_with' => 'First Name and Last Name cannot be empty.',
            'last_name.required_with' => 'First Name and Last Name cannot be empty.',

            'email.required' => 'Email cannot be empty.',
            'email.email' => 'Please enter a correct email address.',

            'phone.required_if' => 'Phone number cannot be empty.',
            'phone.integer' => 'Please enter a correct phone number.',
        ];

        $validator = Validator::make($fields, $rules, $messages);
        $response = new Response();
        if ($validator->fails())
            return $response->json(null, $validator->errors(), HttpResponse::HTTP_UNPROCESSABLE_ENTITY);

        //remove + in country code
        if (isset($fields['country_code']))
        $fields['country_code'] = str_replace('+', '', $fields['country_code']);

        //remove country code when phone is empty
        if (empty($fields['phone']) && $fields['is_mentor'] == false)
        $fields['country_code'] = null;

    $signup = Signup::create($fields);


    return $response->json($signup->toArray(), 'Signup success');
}
}
