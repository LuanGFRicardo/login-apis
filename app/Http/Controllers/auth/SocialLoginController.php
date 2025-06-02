<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialLoginController extends Controller
{
    /**
     * Redireciona o usuário para a página de autenticação do Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtém as informações do usuário do Google e loga/registra.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->scopes([
                'openid',
                'profile',
                'email'
            ])->user();
            
            $findUser = User::where('google_id', $user->id)->first();

            if ($findUser) {
                Auth::login($findUser);
                return redirect('/dashboard');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'avatar' => $user->avatar,
                    'nickname' => $user->nickname,
                    'email_verified' => $user->user['email_verified'] ?? false,
                    'locale' => $user->user['locale'] ?? null,
                    'hd' => $user->user['hd'] ?? null,
                    'given_name' => $user->user['given_name'] ?? null,
                    'family_name' => $user->user['family_name'] ?? null,
                    'password' => bcrypt(Str::random(16)), // Senha aleatória segura
                ]);

                Auth::login($newUser);
                return redirect('/login');
            }

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Erro ao logar com Google: ' . $e->getMessage());
        }
    }

    /**
     * Redireciona o usuário para a página de autenticação da Microsoft.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToMicrosoft()
    {
        return Socialite::driver('microsoft')->redirect();
    }

    /**
     * Obtém as informações do usuário da Microsoft e loga/registra.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleMicrosoftCallback()
    {
        try {
            $user = Socialite::driver('microsoft')->user();

            $findUser = User::where('microsoft_id', $user->id)->first();

            if ($findUser) {
                Auth::login($findUser);
                return redirect('/dashboard');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'microsoft_id' => $user->id,
                    'password' => bcrypt('sua_senha_segura_aqui_ou_null_se_nao_usar'),
                ]);

                Auth::login($newUser);
                return redirect('/dashboard');
            }

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Erro ao logar com Microsoft: ' . $e->getMessage());
        }
    }

    /**
     * Redireciona o usuário para a página de autenticação da Meta (Facebook).
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToMeta()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtém as informações do usuário da Meta (Facebook) e loga/registra.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleMetaCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();

            $findUser = User::where('facebook_id', $user->id)->first();

            if ($findUser) {
                Auth::login($findUser);
                return redirect('/dashboard');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'facebook_id' => $user->id,
                    'password' => bcrypt('sua_senha_segura_aqui_ou_null_se_nao_usar'),
                ]);

                Auth::login($newUser);
                return redirect('/dashboard');
            }

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Erro ao logar com Meta (Facebook): ' . $e->getMessage());
        }
    }
}