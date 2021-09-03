<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Exception;

class SocialController extends Controller
{
    public function redirect($driver)
    {
        session(['driver' => $driver]);
        return Socialite::driver($driver)->with(['driver' => $driver])->redirect();
    }

    public function callback()
    {
        $driver = session()->get('driver');
//        dd($driver);
        try {
            $socialUser = Socialite::driver($driver)->user();
            $user = Customer::where('Oauth_token', $socialUser->id)->first();
            if ($user) {
                Auth::login($user);
                return redirect('/home');
            }

            $user = Customer::where('email', $socialUser->email)->first();
            if ($user) {
                Auth::login($user);
                return redirect('/home');
            } else {
                $createUser = Customer::create([
                    'name' => $socialUser->name,
                    'email' => $socialUser->email,
                    'Oauth_token' => $socialUser->id,
                    'password' => Hash::make('123456789'),
                ]);

                Auth::login($createUser);
                return redirect('/');
            }

        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }



}
