@extends('adminlte::page')

@section('title', 'LISTADO DE USUARIOS - COCINA CON GUSTO')

@section('content_header')
    <h1 class="m-0 text-dark" style="font-size: 2.5rem;">
        <i class="fas fa-users mr-2" style="color: #7a9cc6;"></i>LISTADO DE USUARIOS
    </h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            @if(session('message'))
                <div class="alert alert-{{ session('message.type') }} alert-dismissible fade show auto-close">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fas fa-check-circle mr-2"></i>{{ session('message.text') }}
                </div>
            @endif
            
            <div class="card shadow-sm" style="border-top: 3px solid #a8c2e0;">
                <div class="card-body" style="background-color: #f8fafc;">
                    <table id="users-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><i class="fas fa-cog mr-1" style="color: #7a9cc6;"></i>ACCIONES</th>
                                <th><i class="fas fa-id-card mr-1" style="color: #7a9cc6;"></i>ID</th>
                                <th><i class="fas fa-user mr-1" style="color: #7a9cc6;"></i>NOMBRE</th>
                                <th><i class="fas fa-user mr-1" style="color: #7a9cc6;"></i>APELLIDOS</th>
                                <th><i class="fas fa-venus-mars mr-1" style="color: #7a9cc6;"></i>GENERO</th>
                                <th><i class="fas fa-envelope mr-1" style="color: #7a9cc6;"></i>EMAIL</th>
                                <th><i class="fas fa-phone mr-1" style="color: #7a9cc6;"></i>TELEFONO</th>
                                <th><i class="fas fa-globe-americas mr-1" style="color: #7a9cc6;"></i>PAIS</th>
                                <th><i class="fas fa-calendar-alt mr-1" style="color: #7a9cc6;"></i>FFECHA DE REGISTRO</th>
                                <th><i class="fas fa-info-circle mr-1" style="color: #7a9cc6;"></i>ESTADO</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    $('#users-table').DataTable({
        processing: true,
        serverSide: false,
        data: @json($users),
        columns: [
            { data: 0, name: 'actions', orderable: false, searchable: false },
            { data: 1, name: 'id' },
            { data: 2, name: 'name' },
            { data: 3, name: 'Apellidos' },
            { data: 4, name: 'gender' },
            { data: 5, name: 'email' },
            { data: 6, name: 'phone' },
            { data: 7, name: 'Country' },
            { data: 8, name: 'registration_date' },
            { data: 9, name: 'status' }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json'
        },
        responsive: true,
        autoWidth: false
    });

    // Cierra automáticamente las alertas después de 5 segundos
    setTimeout(function() {
        $('.auto-close').fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 5000);

    window.confirmDelete = function(id) {
        if (confirm('¿Estás seguro de eliminar este usuario?')) {
            document.getElementById('delete-form-'+id).submit();
        }
    }
});
</script>
@endsection

@section('css')
<style>
    body {
        background-color: #f5f7fa;
    }
    .card {
        border-radius: 0.5rem;
        border: none;
    }
    .table thead th {
        background-color: #e9f0f7;
        color: #4a5568;
    }
    .table-bordered {
        border: 1px solid #dee2e6;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(122, 156, 198, 0.05);
    }
    .btn-outline-secondary {
        border-color: #7a9cc6;
        color: #7a9cc6;
    }
    .btn-outline-secondary:hover {
        background-color: #7a9cc6;
        color: white;
    }
    /* Animación para cerrar las alertas */
    .alert {
        transition: opacity 0.5s ease-out;
    }
</style>
@stop