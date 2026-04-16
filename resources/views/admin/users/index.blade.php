@extends('adminlte::page')

@section('title', 'Gestión de usuarios - Cocina con Gusto')

@section('content_header')
    <h1 class="m-0 page-title-my-recipes">GESTIÓN DE USUARIOS</h1>
@stop

@section('css')
<style>
.admin-table-card {
    border-radius: 0.6rem;
    border: none;
    border-top: 3px solid #F28241;
    box-shadow: 0 2px 8px rgba(0,0,0,.07);
}
.admin-table-card .card-body {
    padding: 1.25rem;
}
#users-table thead th {
    background-color: #fff5ee;
    color: #2f2f2f;
    font-size: .8rem !important;
    font-weight: 700;
    letter-spacing: .04em;
    border-bottom: 2px solid #F28241;
    white-space: nowrap;
}
#users-table thead th i {
    color: #F28241;
}
#users-table tbody tr:hover {
    background-color: #fff9f4;
}
#users-table td {
    vertical-align: middle;
    font-size: .9rem !important;
}
.btn-group .btn {
    font-size: .8rem !important;
}
</style>
@stop

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show auto-close mb-3">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('message'))
        <div class="alert alert-{{ session('message.type') }} alert-dismissible fade show auto-close mb-3">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle mr-2"></i>{{ session('message.text') }}
        </div>
    @endif

    <div class="card admin-table-card">
        <div class="card-body">
            <table id="users-table" class="table table-hover table-bordered w-100">
                <thead>
                    <tr>
                        <th><i class="fas fa-cog mr-1"></i>ACCIONES</th>
                        <th><i class="fas fa-user mr-1"></i>NOMBRE</th>
                        <th><i class="fas fa-user mr-1"></i>APELLIDOS</th>
                        <th><i class="fas fa-venus-mars mr-1"></i>GÉNERO</th>
                        <th><i class="fas fa-envelope mr-1"></i>EMAIL</th>
                        <th><i class="fas fa-phone mr-1"></i>TELÉFONO</th>
                        <th><i class="fas fa-map-marker-alt mr-1"></i>ESTADO</th>
                        <th><i class="fas fa-calendar-alt mr-1"></i>REGISTRO</th>
                        <th><i class="fas fa-toggle-on mr-1"></i>ESTATUS</th>
                    </tr>
                </thead>
            </table>
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
            { data: 0, name: 'actions',           orderable: false, searchable: false },
            { data: 1, name: 'name' },
            { data: 2, name: 'last_name' },
            { data: 3, name: 'gender' },
            { data: 4, name: 'email' },
            { data: 5, name: 'phone' },
            { data: 6, name: 'country' },
            { data: 7, name: 'registration_date' },
            { data: 8, name: 'status',            searchable: false },
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json'
        },
        responsive: true,
        autoWidth: false,
        order: [[1, 'asc']],
    });

    setTimeout(function() {
        $('.auto-close').fadeTo(500, 0).slideUp(500, function(){ $(this).remove(); });
    }, 5000);
});
</script>
@endsection