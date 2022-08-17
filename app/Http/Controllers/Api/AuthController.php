<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreUsers;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ApiResponser;

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed'],
            'store_name' => ['required', 'string', 'max:255'],
            'cnpj' => ['required', 'size:14'],
            'contact' => ['required', 'email'],
        ]);

        $store = Store::create([
            'name' => $request->store_name,
            'cnpj' => $request->cnpj,
            'contact' => $request->contact,
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "admin" => true
        ]);

        StoreUsers::create([
            'user_id' => $user->id,
            'store_id' => $store->id
        ]);

        return $this->success(null, 'Usuário registrado com sucesso');
    }

    public function getUser(Request $request)
    {
        $user = User::find($request->user()->id)->with('store');

        return $this->success($user);
    }

    public function registerEmployee(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed'],
            'admin' => ['required', 'boolean']
        ]);

        $userAuth = User::find($request->user()->id);
        $store = $userAuth->store()->first();

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "admin" => $request->admin
        ]);

        $store = StoreUsers::create([
            'user_id' => $user->id,
            'store_id' => $store->id
        ]);

        $user = User::find($user->id)->with('store');

        return $this->success(null, 'Usuário registrado com sucesso');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = User::find(Auth::user()->id);
            $user->tokens()->delete();
            $token = $user->createToken('JWT')->plainTextToken;
            return $this->success(['token' => $token, 'user' => $user]);
        }

        return $this->error('E-mail ou senha inválido', 401);
    }

    public function logout()
    {
        $user = User::find(Auth::user()->id);
        $user->tokens()->delete();
        return $this->success(null, 'Logout realizado com sucesso');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status == Password::RESET_LINK_SENT) {
            return $this->success(null, __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);

        return $this->success(null, 'E-mail enviado com sucesso');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return $this->success(null, 'Senha alterada com sucesso');
        }

        return $this->error(__($status), 500);
    }

}
