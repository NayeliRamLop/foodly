<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Ruta de redirección después del restablecimiento
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Muestra el formulario de restablecimiento de contraseña
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with([
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Reglas de validación para el restablecimiento
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ],
        ];
    }

    /**
     * Mensajes de error personalizados
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [
            'password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula y un número.',
        ];
    }

    /**
     * Obtiene las credenciales de la solicitud
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only(
            'email', 
            'password', 
            'password_confirmation', 
            'token'
        );
    }

    /**
     * Resetea la contraseña del usuario
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Eliminar el token usado
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        // Puedes agregar una notificación si lo deseas
        // $user->notify(new PasswordChangedNotification());
    }

    /**
     * Método para manejar el POST del formulario de restablecimiento
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

    $response = $this->broker()->reset(
        $this->credentials($request),
        function ($user, $password) {
            $this->resetPassword($user, $password);
        }
    );

    if ($request->expectsJson()) {
        // Si la petición es AJAX, responde JSON
        return $response == Password::PASSWORD_RESET
            ? response()->json(['message' => trans($response)])
            : response()->json(['message' => trans($response)], 422);
    }

    // Si no es AJAX, usa la respuesta normal (redirección)
    return $response == Password::PASSWORD_RESET
        ? $this->sendResetResponse($request, $response)
        : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * Respuesta exitosa de restablecimiento
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return redirect($this->redirectPath())
            ->with('status', trans($response));
    }

    /**
     * Respuesta fallida de restablecimiento
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }
}
