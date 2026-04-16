@extends('adminlte::page')

@section('title', 'Gestión de recetas - Cocina con Gusto')

@section('content_header')
    <h1 class="m-0 page-title-my-recipes">GESTIÓN DE RECETAS</h1>
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
#recipes-table thead th {
    background-color: #fff5ee;
    color: #2f2f2f;
    font-size: .8rem !important;
    font-weight: 700;
    letter-spacing: .04em;
    border-bottom: 2px solid #F28241;
    white-space: nowrap;
}
#recipes-table thead th i {
    color: #F28241;
}
#recipes-table tbody tr:hover {
    background-color: #fff9f4;
}
#recipes-table td {
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
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show auto-close mb-3">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="card admin-table-card">
        <div class="card-body">
            <table id="recipes-table" class="table table-hover table-bordered w-100">
                <thead>
                    <tr>
                        <th><i class="fas fa-cog mr-1"></i>ACCIONES</th>
                        <th><i class="fas fa-heading mr-1"></i>TÍTULO</th>
                        <th><i class="fas fa-user mr-1"></i>USUARIO</th>
                        <th><i class="fas fa-tag mr-1"></i>CATEGORÍA</th>
                        <th><i class="fas fa-calendar-alt mr-1"></i>FECHA CREACIÓN</th>
                        <th><i class="fas fa-toggle-on mr-1"></i>ESTATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recipes as $recipe)
                    <tr>
                        <td>{!! $recipe['actions'] !!}</td>
                        <td>{{ $recipe['title'] }}</td>
                        <td>{{ $recipe['user'] }}</td>
                        <td>{{ $recipe['category'] }}</td>
                        <td>{{ $recipe['created_at'] }}</td>
                        <td>
                            <span class="badge badge-{{ $recipe['status'] == 'Activo' ? 'success' : 'secondary' }}">
                                {{ $recipe['status'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
$(document).ready(function() {
    $('#recipes-table').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json'
        },
        responsive: true,
        autoWidth: false,
        order: [[1, 'asc']],
        columnDefs: [
            { orderable: false, targets: 0 }
        ]
    });

    setTimeout(function() {
        $('.auto-close').fadeTo(500, 0).slideUp(500, function(){ $(this).remove(); });
    }, 5000);

    window.confirmDelete = function(id) {
        if (confirm('¿Estás seguro de desactivar esta receta?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    };
});
</script>
@stop