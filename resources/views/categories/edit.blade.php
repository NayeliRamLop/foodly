@extends('adminlte::page')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 mb-4">
            <h2>Editar categoría</h2>
        </div>
        <div class="col-lg-7">
            <form action="{{ route('categories.update', $category->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="form-group">
                    <label for="category_name">Nombre de la categoría</label>
                    <input type="text" class="form-control" id="category_name" name="category_name" value="{{ $category->category_name }}">
                </div>
                <div class="form-group">
                    <label for="category_description">Descripción</label>
                    <textarea class="form-control" id="category_description" name="category_description">{{ $category->category_description }}</textarea>
                </div>
                <button type="submit" class="btn btn-success">Guardar Categoría</button>
            </form>
        </div>
    </div>
</div>
@endsection
