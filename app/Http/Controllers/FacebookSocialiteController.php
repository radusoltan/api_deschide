<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class FacebookSocialiteController extends Controller
{
    public function redirectToFB()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleCallback() {
        try {

            $user = Socialite::driver('facebook')->user();

            $finduser = User::where('social_id', $user->id)->first();

            if($finduser){

                Auth::login($finduser);

                return redirect()->to('http:://localhost:3000');

            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id'=> $user->id,
                    'social_type'=> 'facebook',
                    'password' => encrypt('my-facebook')
                ]);

                Auth::login($newUser);

                return redirect()->to('http:://localhost:3000');
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
