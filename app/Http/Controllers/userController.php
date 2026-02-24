<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function adminIndex()
    {
        $users = User::all(); // Obtener todos los usuarios
        return view('admin.users.index', ['users' => $this->cargarDT($users)]);
    }

    public function index()
    {
        $users = User::where('status', 1)->get();
        return view('user.index', ['users' => $this->cargarDT($users)]);
    }

    public function perfil()
    {
        $user = Auth::user();
        return view('user.perfil', compact('user'));
    }

    private function cargarDT($consulta)
{
    $usuarios = [];
    foreach ($consulta as $key => $value) {
        $actualizar = route('user.edit', $value['id']);
        $toggleStatus = route('user.toggle-status', $value['id']);
        
        $statusBtn = $value['status'] == 1 
            ? '<a href="'.$toggleStatus.'" class="btn btn-warning btn-sm" title="Desactivar"><i class="fas fa-toggle-off"></i></a>'
            : '<a href="'.$toggleStatus.'" class="btn btn-success btn-sm" title="Activar"><i class="fas fa-toggle-on"></i></a>';

        $acciones = '
        <div class="btn-group">
            <a href="'.$actualizar.'" class="btn btn-success btn-sm" title="Editar">
                <i class="far fa-edit"></i>
            </a>
            '.$statusBtn.'
        </div>';

        $usuarios[$key] = array(
            $acciones,
            $value['id'],
            $value['name'],
            $value['last_name'],
            $value['gender'],
            $value['email'],
            $value['phone'],
            $value['country'],
            $value['registration_date'],
            $value['status'] == 1 ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>'
        );
    }
    return $usuarios;
}
    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
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
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => $request->country,
            'registration_date' => $request->registration_date,
            'password' => bcrypt($request->password),
            'status' => 1,
            'avatar' => $avatarPath,
        ]);

        return Auth::check() 
            ? redirect()->route('user.index')->with('success', 'Usuario creado correctamente')
            : redirect()->route('login')->with('success', 'Registro exitoso. Por favor inicie sesión.');
    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('profile.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'registration_date' => 'date',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->update([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => $request->country,
            'registration_date' => $request->registration_date,
            'password' => $request->filled('password') ? bcrypt($request->password) : $user->password,
        ]);

        return redirect()->route('user.edit', $user->id)->with('status', 'Usuario actualizado correctamente');
    }

    public function updateAvatar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        $user = User::findOrFail($id);

        try {
            // Eliminar avatar anterior si existe
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Guardar nuevo avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
            $user->save();

            return back()->with([
                'success' => 'Avatar actualizado correctamente',
                'avatar_url' => asset('storage/'.$path)
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el avatar: '.$e->getMessage());
        }
    }

    public function deleteAvatar($id)
    {
        $user = User::findOrFail($id);

        try {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = null;
            $user->save();

            return back()->with('success', 'Avatar eliminado correctamente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el avatar: '.$e->getMessage());
        }
    }

    public function destroy($id)
{
    try {
        // Buscar el usuario por ID
        $user = User::findOrFail($id);
        
        // Verificar si el usuario está eliminando su propia cuenta
        $isDeletingOwnAccount = $user->id === auth()->id();
        
        // Cambiar el estado a 0 (eliminación lógica) en lugar de eliminar
        $user->status = 0;
        $user->save();
        
        // Si está eliminando su propia cuenta, cerrar sesión y redirigir al login
        if ($isDeletingOwnAccount) {
            auth()->logout();
            return redirect()->route('login')->with('success', 'Tu cuenta ha sido desactivada correctamente');
        }
        
        // Si es un admin desactivando otra cuenta, redirigir de vuelta
        return redirect()->back()->with('success', 'Usuario desactivado correctamente');
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error al desactivar el usuario');
    }
}public function toggleStatus($id)
{
    try {
        $user = User::findOrFail($id);
        $user->status = $user->status == 1 ? 0 : 1; // Alternar estado
        $user->save();

        $action = $user->status == 1 ? 'activada' : 'desactivada';
        return redirect()->back()->with('success', "Cuenta $action correctamente");
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error al cambiar el estado: '.$e->getMessage());
    }
}
}