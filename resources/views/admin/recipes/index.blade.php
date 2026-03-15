@extends('adminlte::page')

@section('title', 'ADMINISTRACIÓN DE RECETAS - COCINA CON GUSTO')

@section('content_header')
    <h1 class="m-0 text-dark" style="font-size: 2.5rem;">
        <i class="fas fa-utensils mr-2" style="color: #F28241;"></i>ADMINISTRACIÓN DE RECETAS
    </h1>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show auto-close">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show auto-close">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif
    </div>
</div>

<div class="row justify-content-center mt-4">
    <div class="col-12">
        <div class="card shadow-sm" style="border-top: 3px solid #a8c2e0;">
            <div class="card-body" style="background-color: #f8fafc;">
                <div class="table-responsive">
                    <table id="recipes-table" class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 120px; color: #4a5568;">
                                    <i class="fas fa-cog mr-2" style="color: #F28241;"></i>ACCIONES
                                </th>
                                <th style="color: #4a5568;">
                                    <i class="fas fa-hashtag mr-2" style="color: #F28241;"></i>ID
                                </th>
                                <th style="color: #4a5568;">
                                    <i class="fas fa-heading mr-2" style="color: #F28241;"></i>TÍTULO
                                </th>
                                <th style="color: #4a5568;">
                                    <i class="fas fa-user mr-2" style="color: #F28241;"></i>USUARIO
                                </th>
                                <th style="color: #4a5568;">
                                    <i class="fas fa-tag mr-2" style="color: #F28241;"></i>CATEGORÍA
                                </th>
                                <th style="color: #4a5568;">
                                    <i class="fas fa-tags mr-2" style="color: #F28241;"></i>SUBCATEGORÍA
                                </th>
                                <th style="color: #4a5568;">
                                    <i class="fas fa-calendar-alt mr-2" style="color: #F28241;"></i>FECHA CREACIÓN
                                </th>
                                <th style="color: #4a5568;">
                                    <i class="fas fa-check-circle mr-2" style="color: #F28241;"></i>ESTADO
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recipes as $recipe)
                            <tr>
                                <td>{!! $recipe['actions'] !!}</td>
                                <td>{{ $recipe['id'] }}</td>
                                <td>{{ $recipe['title'] }}</td>
                                <td>{{ $recipe['user'] }}</td>
                                <td>{{ $recipe['category'] }}</td>
                                <td>{{ $recipe['subcategory'] }}</td>
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
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('#recipes-table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        },
        "responsive": true,
        "autoWidth": false,
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        ]
    });

    // Cierra automáticamente las alertas después de 3 segundos
    setTimeout(function() {
        $('.auto-close').fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 5000);

    window.confirmDelete = function(id) {
        if (confirm('¿Estás seguro de desactivar esta receta?')) {
            document.getElementById('delete-form-'+id).submit();
        }
    }
});
</script>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
<style>
    body {
        background-color: #f5f7fa;
    }
    .card {
        border-radius: 0.5rem;
        border: none;
    }
    .table thead th {
        border-bottom: 2px solid #a8c2e0;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(122, 156, 198, 0.1);
    }
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
    .btn-sm {
        margin: 0 2px;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .btn-group {
        display: flex;
        flex-wrap: nowrap;
    }
    /* Animación para cerrar las alertas */
    .alert {
        transition: opacity 0.5s ease-out;
    }
</style>
@stop