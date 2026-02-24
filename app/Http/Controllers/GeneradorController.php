<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use App\Models\User;

class GeneradorController extends Controller
{
  public function imprimir(){
  $today = Carbon::now()->format('d/m/Y');
  $pdf = \PDF::loadView('ejemplo', compact('today'));
  return $pdf->download('ejemplo.pdf');
}
public function imprimirusuarios()
{
    $usuarios = User::where('status', '1')->get();
    $pdf = \PDF::loadView('user.userpdf', compact('usuarios'));
    return $pdf->download('usuarios.pdf');
}
}

