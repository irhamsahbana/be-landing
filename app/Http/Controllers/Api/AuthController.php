<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Libs\Response;
use App\Mail\SendEmailVerificationLink;

use App\Models\Signup;
use Illuminate\Support\Facades\Mail;

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
        $response = new Response();
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
            'first_name' => ['required_without:last_name', 'string', 'max:255'],
            'last_name' => ['required_without:first_name', 'string', 'max:255'],
            'email' => ['required', 'email:rfc,dns'],
            'country_code' => [
                'nullable',
                'integer',
                'between:1,999',
            ],
            'phone' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:150'],
        ];

        $messages = [
            'industry_id.required' => 'Please choose one industry.',

            'first_name.required_without' => 'First Name and Last Name cannot be empty.',
            'last_name.required_without' => 'First Name and Last Name cannot be empty.',

            'first_name.max' => 'First Name must be less than 255 characters.',
            'last_name.max' => 'Last Name must be less than 255 characters.',

            'email.required' => 'Email cannot be empty.',
            'email.email' => 'Please enter a correct email address.',

            'phone.string' => 'Please enter a correct phone number.',

            'message.max' => 'Message cannot be more than 150 characters.',
        ];

        $validator = Validator::make($fields, $rules, $messages);

        if ($validator->fails())
            return $response->json(null, $validator->errors(), HttpResponse::HTTP_UNPROCESSABLE_ENTITY);

        //remove + in country code
        if (isset($fields['country_code']))
            $fields['country_code'] = str_replace('+', '', (string) $fields['country_code']);

        //remove all characters except numbers in phone number
        if (isset($fields['phone']) && !empty($fields['phone']))
            $fields['phone'] = preg_replace('/[^0-9]/', '', $fields['phone']);

        //remove phone number and country code if phone number is empty
        if (empty($fields['phone'])) {
            unset($fields['phone']);
            unset($fields['country_code']);
        }

        DB::beginTransaction();
        try {
            $token = Str::uuid()->toString();
            $fields['token'] = $token;
            $signup = Signup::create($fields);

            // send email to email address
            $name = $signup->first_name . ' ' . $signup->last_name;
            $email = new SendEmailVerificationLink($name, $fields['email'], $token);
            Mail::to($fields['email'])->send($email);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $response->json(null, $e->getMessage(), HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response->json($signup->toArray(), 'Signup success');
    }
}
