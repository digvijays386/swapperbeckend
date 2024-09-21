<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserIntrest_pivot;
use Illuminate\Support\Facades\Hash;
use App\Mail\OtpMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // dd(Carbon::now()->format('Y-m-d H:i:s'));
        DB::beginTransaction();
        try {
            $user = User::where('email', $request->email)->first();
            $this->validate(
                $request,
                ['name' => 'required'],
                ['address' => 'required'],
                ['lat' => 'required'],
                ['lng' => 'required'],
                ['surname' => 'required'],
                ['age' => 'required'],
                // ['county' => 'required'],
                ['password' => 'required'],
                ['password_confirmation' => 'required'],
                ['profile_image' => 'required']
            );
            if (strlen($request->name) < 4) {
                return   response()->json([
                    "message" => "The name must be at least 4 characters.",
                    'error' => True
                ]);
            } elseif (strlen($request->password) < 8) {
                return   response()->json([
                    "message" => "The password must be at least 8 characters.",
                    'error' => True
                ]);
            } elseif ($request->password != $request->password_confirmation) {
                return   response()->json([
                    "message" => "The password does not match.",
                    'error' => True
                ]);
            } elseif ($user != null) {
                return   response()->json([
                    "message" => "Email already taken",
                    'error' => True
                ]);
            } elseif (strlen($request->surname) < 4) {
                return   response()->json([
                    "message" => "The surname must be at least 4 characters.",
                    'error' => True
                ]);
            } elseif (($request->age) < 13) {
                return   response()->json([
                    "message" => "The age must be at least 13 years.",
                    'error' => True
                ]);
            }
            elseif ($request->profile_image == null) {
                return   response()->json([
                    "message" => "Profile Image is required",
                    'error' => True
                ]);
            }
            else {
                $description = "Verification code ";
                $otp = rand(1111, 9999);
                $address = explode(",", $request->address);
                // dd(count($address));
                if (count($address) == 1) {
                    $city = $address[0];
                    $country = null;
                } elseif (count($address) == 2) {
                    $city = $address[0];
                    $country = $address[1];
                } else {
                    $country = array_pop($address);
                    // dump($country);
                    // $ex = array_pop($address);
                    $city = implode(',', $address);
                    // dd($city);
                }

                if ($request->has('profile_image')) {
                    // if (is_string($request->profile_image)) {
                    //     $file = $request->profile_image;
                    //     list($mime, $data)   = explode(';', $file);
                    //     list(, $data)       = explode(',', $data);
                    //     $data = base64_decode($data);
                    //     $mime = explode(':', $mime)[1];
                    //     $ext = explode('/', $mime)[1];
                    //     $name = mt_rand() . time();
                    //     $savePath = 'profiles/' . $name . '.' . $ext;
                    //     file_put_contents(public_path() . '/' . $savePath, $data);
                    //     // dd('done');
                    // } else {
                        $file = $request->profile_image;
                        // dd($file);
                        $file_name = $file->store('public/profiles');
                        Storage::delete($file_name);
                        $destinationPath = public_path() . '/profiles/';
                        $file->move($destinationPath, $file_name);
                    // }
                }
                Mail::to($request->email)->send(new OtpMail($description . $otp));
                $user = User::where('email', $request->email)->create([
                    'email_otp' => $otp,
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'address' => $city,
                    'lat' => $request->lat,
                    'lng' => $request->lng,
                    'surname' => $request->surname,
                    'age' => $request->age,
                    'country' => $country,
                    'profile_image' => (isset($file_name) ? str_replace("public/profiles/", "", $file_name) : '')
                ]);
                $token = $user->createToken('LaravelAuthApp')->accessToken;
                DB::commit();

                if ($user) {
                    return response()->json([
                        "data" => [
                            "token" => $token,
                            "id" => $user->id,
                            "name" => $user->name,
                            "email" => $user->email,
                            "address" => $user->address,
                            "lat" => $user->lat,
                            "lng" => $user->lng,
                            "country" => $user->country,
                            "profile_image" => $user->profile_image,
                            "email_verified_at" => $user->email_verified_at
                        ],
                        "message" => "User Register Successfully!",
                        'error' => False

                    ]);
                } else {
                    return response()->json([
                        "message" => "Validation Error",
                        'error' => True
                    ]);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ]);
            //throw $th;
        }
    }
    /**
     * Login
     */
    public function login(Request $request)
    {

        $description = "Verification code ";
        $otp = rand(1111, 9999);
        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Hash::Check($request->password, $user->password)) {
                $interest = UserIntrest_pivot::where('user_id', $user->id)->get();
                $token = $user->createToken('LaravelAuthApp')->accessToken;

                if (count($interest) > 0)
                    $interestStatus = true;
                else
                    $interestStatus = false;
                if ($user->email_otp == 0) {
                    User::where('email', $request->email)->update(['email_otp' => $otp]);
                    Mail::to($request->email)->send(new OtpMail($description . $otp));
                }
                return response()->json([
                    "data" => [
                        "token" => $token,
                        "otp" => $otp,
                        "id" => $user->id,
                        "email_otp" => $user->email_otp,
                        "name" => $user->name,
                        "email" => $user->email,
                        "surname" => $user->surname,
                        "profile_image" => $user->profile_image,
                        "status" => $user->status,
                        "interest_status" => $interestStatus,
                        "email_verified_at" => $user->email_verified_at
                    ],
                    "message" => "User Login Successfully!",
                    'error' => False

                ]);

            } else {
                return response()->json([
                    "message" => "Password Missmatch",
                    'error' => True
                ]);
            }
        } else {
            return response()->json([
                "message" => "user Not Found",
                'error' => True
            ]);
        }
    }
    public function match_otp_email(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user != null) {
            if ($user->email_otp == $request->otp) {
                // $user->update(['email_otp' => 1]);
                $user->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
                $user->update();
                $token = $user->createToken('token generated')->accessToken;

                $response = [
                    'token' => $token,
                    'user' => $user,
                    "message" => "Match Otp Successfully!",
                    'error' => False
                ];
                return response($response, 200);
            } else {
                return response()->json(['message' => 'otp mismatch', 'error' => True], 200);
            }
        } else {
            return response()->json(['message' => 'user does not exist!', 'error' => True], 200);
        }
    }
    public function match_otp(Request $request)
    {

        $user = User::where('email', $request->email)->first();
        if ($user != null) {
            if ($user->email_otp == $request->otp) {
                // $user->update(['otp' => null]);
                $user->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
                $user->update();
                $token = $user->createToken('token generated')->accessToken;
                $response = [
                    'token' => $token,
                    'user' => $user,
                    'message' => 'otp matched successfully',
                    'error' => False
                ];
                return response($response, 200);
            } else {
                return response()->json(['message' => 'otp mismatch', 'error' => True], 200);
            }
        } else {
            return response()->json(['message' => 'user does not exist!', 'error' => True], 200);
        }
    }

    public function reset_password(Request $request)
    {

        $request->validate([
            'password' => 'required|min:8',
            'confirm_password' => 'required',
        ]);
        if ($request->password == $request->confirm_password) {
            Auth::user()->update(['password' => Hash::make($request->password)]);
            return response()->json([
                'message' => 'Password Reset Successfully',
                'error' => FALSE

            ]);
        } else {
            return response()->json([
                'message' => 'Your Confirm Password Does Not Match With Your Password',
                'error' => True

            ]);
        }
    }

    public function logout()
    {
        auth()->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully Logout',
            'error' => FALSE


        ]);
    }
    public function send_email(Request $request)
    {

        $request->validate([
            'email' => 'required',
        ]);
        if (User::where('email', $request->email)->doesntExist()) {
            return response()->json([
                'message' => "Your Email Does Not Exist",
                'error' => TRUE
            ]);
        } else {
            $description = "Verification code ";
            $otp = rand(1111, 9999);
            $user = User::where('email', $request->email)->update(['email_otp' => $otp]);
            Mail::to($request->email)->send(new OtpMail($description . $otp));
            return response()->json([
                'message' => 'Please Kindly Check Your Email',
                'error' => FALSE

            ]);
        }
    }
    public function update_password(Request $request)
    {

        $request->validate([
            'new_password' => 'required|min:8',
        ]);
        if (Hash::check($request->old_password, auth()->user()->password)) {

            if ($request->new_password == $request->confirm_password) {
                Auth::user()->update(['password' => Hash::make($request->new_password)]);
                return response()->json([
                    'message' => 'Password Has Been Updated',
                    'error' => FALSE
                ]);
            } else {
                return response()->json([
                    'message' => 'Your Confirm Password Does Not Match With Your New Password',
                    'error' => TRUE
                ], 403);
            }
        } else {
            return response()->json([
                'message' => 'Your Old Password Does Not Match',
                'error' => TRUE
            ], 403);
        }
    }
    public function email()
    {
        $description = "Verification code ";

        $otp = rand(1111, 9999);
        // dump($country);
        // dd($city);

        Mail::to('afzaal.oranjetech@gmail.com')->send(new OtpMail($description . $otp));
        dd('done');
    }
}
