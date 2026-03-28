<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GuestRecipeAuthController extends Controller
{
    public function register(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'registration_date' => 'required|date',
            'password' => 'required|string|confirmed|min:6',
            'redirect_to' => 'nullable|string|max:2048',
            'return_to' => 'nullable|string|max:2048',
            'auth_modal_tab' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => $request->country,
            'registration_date' => $request->registration_date,
            'password' => bcrypt($request->password),
            'status' => 1,
        ]);

        return redirect()->to($request->input('return_to', url('/')))
            ->with('success', 'Tu cuenta ya está lista. Inicia sesión para ver tu receta.')
            ->with('guest_recipe_open_modal', true)
            ->with('guest_recipe_modal_tab', 'login')
            ->with('guest_recipe_redirect_to', $request->input('redirect_to'));
    }
}
