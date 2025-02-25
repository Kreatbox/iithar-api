<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phoneNumber' => 'nullable|string|max:255',
            'birthDate' => 'required|date',
            'bloodType' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'banks_id' => 'required|exists:banks,id',  
            'gender' => 'required|boolean',
            'formAnswer' => 'required|string',
            'ssid' => 'required|string',
            'password' => 'required|string|min:9',
        ], [
            'firstName.required' => 'الاسم الأول مطلوب.',
            'firstName.string' => 'الاسم الأول يجب أن يكون نصًا.',
            'firstName.max' => 'الاسم الأول يجب أن لا يزيد عن 255 حرفًا.',
            'lastName.required' => 'اسم العائلة مطلوب.',
            'lastName.string' => 'اسم العائلة يجب أن يكون نصًا.',
            'lastName.max' => 'اسم العائلة يجب أن لا يزيد عن 255 حرفًا.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.string' => 'البريد الإلكتروني يجب أن يكون نصًا.',
            'email.email' => 'البريد الإلكتروني يجب أن يكون عنوان بريد إلكتروني صالح.',
            'email.max' => 'البريد الإلكتروني يجب أن لا يزيد عن 255 حرفًا.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',
            'phoneNumber.max' => 'رقم الهاتف يجب أن لا يزيد عن 255 حرفًا.',
            'birthDate.required' => 'تاريخ الميلاد مطلوب.',
            'birthDate.date' => 'تاريخ الميلاد يجب أن يكون تاريخًا صالحًا.',
            'bloodType.required' => 'نوع الدم مطلوب.',
            'bloodType.in' => 'نوع الدم يجب أن يكون واحدًا من: A+, A-, B+, B-, AB+, AB-, O+, O-.',
            'banks_id.required' => 'معرف البنك مطلوب.',
            'banks_id.exists' => 'معرف البنك غير موجود.',
            'gender.required' => 'الجنس مطلوب.',
            'gender.boolean' => 'الجنس يجب ان يكون اما ذكر او انثى',
            'formAnswer.required' => 'إجابة النموذج مطلوبة.',
            'formAnswer.string' => 'إجابة النموذج يجب أن تكون نصًا.',
            'ssid.required' => 'معرف الخدمة مطلوب.',
            'ssid.string' => 'معرف الخدمة يجب أن يكون نصًا.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.string' => 'كلمة المرور يجب أن تكون نصًا.',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 9 أحرف.',
            'password.confirmed' => 'تأكيد كلمة المرور لا يتطابق.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'phoneNumber' => $request->phoneNumber,
            'birthDate' => $request->birthDate,
            'bloodType' => $request->bloodType,
            'banks_id' => $request->banks_id,
            'gender' => $request->gender,
            'formAnswer' => $request->formAnswer,
            'ssid' => $request->ssid,
            'password' => Hash::make($request->password),
            'role' => 0, 
            'validated' => false,
        ]);

        $token = $user->createToken('YourAppName')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = $user->createToken('YourAppName')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user], 200);
    }




    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }


}
