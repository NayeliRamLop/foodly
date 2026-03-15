mostrar tabla pera administrar usuarios
/*
@extends('adminlte::page')
@section('css')
<link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
@vite(['resources/sass/app.scss', 'resources/js/app.js'])   
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.dataTables.css">
@endsection

@section('content')
<div class="container">
    <div class="row">
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
    </div>
    <div class="row">
        <h2>Lista de Usuarios</h2>
        <hr><br>
        <p align="right">
            <a href="{{ route('user.create') }}" class="btn btn-success">Crear Usuario</a>
            <a href="{{ route('home') }}" class="btn btn-primary">Regresar</a>
        </p>
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Acciones</th>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $user)
                     <tr>
        <td>
            <a href="{{ route('user.edit', $user[1]) }}" class="btn btn-warning btn-sm">Editar</a>
            <button class="btn btn-danger btn-sm" onclick="modal('{{ $user[1] }}', '{{ $user[2] }}')">Eliminar</button>
        </td>
        <td>{{ $user[1] }}</td>
        <td>{{ $user[2] }}</td>
        <td>{{ $user[3] }}</td>
    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Confirmar eliminación</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Estás segura de eliminar al usuario <strong><span id="nombre"></span></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" id="borrar" class="btn btn-danger">Eliminar</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function modal(id, nombre) {
        $('#nombre').text(nombre);
        let url = "{{ route('deleteUser', ':id') }}";
        url = url.replace(':id', id);
        document.getElementById('borrar').href = url;
        $('#exampleModal').modal('show');
    }
</script>
@endsection