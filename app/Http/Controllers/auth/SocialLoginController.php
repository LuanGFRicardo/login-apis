<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\UserGoogle;
use App\Models\UserMicrosoft;
use App\Models\UserMeta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                'email',
                'https://www.googleapis.com/auth/user.birthday.read',
                'https://www.googleapis.com/auth/user.phonenumbers.read',
                'https://www.googleapis.com/auth/user.addresses.read'
            ])->user();

            if (!$user || !$user->id) {
                Log::warning('Callback Google sem dados de usuário ou ID.');
                return redirect('/login')->with('error', 'Não foi possível obter os dados do Google.');
            }

            $findUser = UserGoogle::where('google_id', $user->id)->first();

            if ($findUser) {
                Auth::login($findUser);

                return redirect('/login')->with('success', 'Login com Google realizado com sucesso!');
            } else {
                $newUser = UserGoogle::create([
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
                    'profile_url' => $user->user['profile'] ?? null,
                    'updated_at_google' => $user->user['updated_at'] ?? null,
                    'gender' => $user->user['gender'] ?? null,
                    'birthdate' => isset($user->user['birthdate']) ? Carbon::parse($user->user['birthdate']) : null,
                    'phone_number' => $user->user['phone_number'] ?? null,
                    'address' => $user->user['address'] ?? null,
                    'password' => bcrypt(Str::random(16)),
                ]);

                return redirect('/login')->with('success', 'Conta Google registrada com sucesso!');
            }

        } catch (\Exception $e) {
            Log::error('Erro ao logar com Google: ' . $e->getMessage(), [
                'exception' => $e
            ]);

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
        $query = http_build_query([
            'client_id' => config('services.microsoft.client_id'),
            'redirect_uri' => config('services.microsoft.redirect'),
            'response_type' => 'code',
            'scope' => 'openid profile email offline_access User.Read',
            'response_mode' => 'query',
        ]);

        return redirect(config('services.microsoft.authorize_url') . '?' . $query);
    }

    /**
     * Obtém as informações do usuário da Microsoft e loga/registra.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleMicrosoftCallback(Request $request)
    {
        try {
            $code = $request->get('code');

            if (!$code) {
                Log::warning('Callback Microsoft sem código');
                return redirect('/login')->with('error', 'Código ausente.');
            }

            $tokenResponse = Http::asForm()->post(config('services.microsoft.token_url'), [
                'client_id' => config('services.microsoft.client_id'),
                'client_secret' => config('services.microsoft.client_secret'),
                'code' => $code,
                'redirect_uri' => config('services.microsoft.redirect'),
                'grant_type' => 'authorization_code',
            ]);

            if ($tokenResponse->failed()) {
                Log::error('Token Microsoft falhou', [
                    'status' => $tokenResponse->status(),
                    'body' => $tokenResponse->body()
                ]);
                return redirect('/login')->with('error', 'Falha ao obter token.');
            }

            $tokenData = $tokenResponse->json();

            $accessToken = $tokenData['access_token'];
            $refreshToken = $tokenData['refresh_token'] ?? null;
            $expiresIn = $tokenData['expires_in'];

            $userResponse = Http::withToken($accessToken)->get(config('services.microsoft.resource_url'));

            if ($userResponse->failed()) {
                Log::error('Falha ao obter dados do usuário', [
                    'status' => $userResponse->status(),
                    'body' => $userResponse->body()
                ]);
                return redirect('/login')->with('error', 'Falha ao obter dados do usuário.');
            }

            $user = $userResponse->json();

            $microsoftId = $user['id'] ?? null;

            if (!$microsoftId) {
                Log::error('Falha ao obter id do usuário', [
                    'status' => $userResponse->status(),
                    'body' => $userResponse->body()
                ]);
                return redirect('/login')->with('error', 'ID do usuário não encontrado.');
            }

            $findUser = UserMicrosoft::where('microsoft_id', $microsoftId)->first();

            if ($findUser) {
                $findUser->update([
                    'microsoft_access_token' => $accessToken,
                    'microsoft_refresh_token' => $refreshToken,
                    'microsoft_token_expires_at' => now()->addSeconds($expiresIn),
                ]);

                return redirect('/login')->with('success', 'Login com Microsoft realizado com sucesso!');
            } else {
                $newUser = UserMicrosoft::create([
                    'microsoft_id' => $microsoftId,
                    'display_name' => $user['displayName'] ?? null,
                    'given_name' => $user['givenName'] ?? null,
                    'surname' => $user['surname'] ?? null,
                    'user_principal_name' => $user['userPrincipalName'] ?? null,
                    'mail' => $user['mail'] ?? null,
                    'mobile_phone' => $user['mobilePhone'] ?? null,
                    'business_phones' => json_encode($user['businessPhones'] ?? []),
                    'job_title' => $user['jobTitle'] ?? null,
                    'company_name' => $user['companyName'] ?? null,
                    'office_location' => $user['officeLocation'] ?? null,
                    'preferred_language' => $user['preferredLanguage'] ?? null,
                    'microsoft_access_token' => $accessToken,
                    'microsoft_refresh_token' => $refreshToken,
                    'microsoft_token_expires_at' => now()->addSeconds($expiresIn),
                    'microsoft_avatar' => null,
                ]);

                return redirect('/login')->with('success', 'Conta Microsoft registrada com sucesso!');
            }
        } catch (\Exception $e) {
            Log::error('Erro ao salvar usuário Microsoft: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Erro interno ao salvar dados.');
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
            $facebookUser = Socialite::driver('facebook')->stateless()->user();

            $userMeta = UserMeta::updateOrCreate(
                ['provider_id' => $facebookUser->getId()],
                [
                    'name' => $facebookUser->getName(),
                    'email' => $facebookUser->getEmail(),
                    'nickname' => $facebookUser->getNickname(),
                    'avatar' => $facebookUser->getAvatar(),
                    'token' => $facebookUser->token,
                    'refresh_token' => $facebookUser->refreshToken ?? null,
                    'expires_in' => $facebookUser->expiresIn,
                    'raw_data' => json_encode($facebookUser),
                ]
            );

            return redirect('/login')->with('success', 'Login com Facebook realizado com sucesso!');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Erro ao autenticar com Facebook: ' . $e->getMessage());
        }
    }
}