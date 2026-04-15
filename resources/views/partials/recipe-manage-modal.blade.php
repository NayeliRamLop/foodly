<div class="modal fade recipe-manage-modal" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $titleId }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="{{ $titleId }}">
                    <i class="fas fa-plus-circle mr-2"></i> Agregar receta
                </h4>
                <button type="button" class="close modal-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="recipeManageForm" action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="recipeManageMethod" name="_method" value="POST" disabled>
                <input type="hidden" id="recipeFormMode" name="_recipe_form_mode" value="{{ old('_recipe_form_mode', 'create') }}">
                <input type="hidden" id="recipeFormRecipeId" name="_recipe_id" value="{{ old('_recipe_id') }}">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div class="recipe-manage-shell">
                                <div class="recipe-manage-section-title">
                                    <i class="fas fa-pen"></i> Informacion general
                                </div>

                                <div class="form-group">
                                    <label for="manage_title">Titulo de la receta *</label>
                                    <input type="text" class="form-control" id="manage_title" name="title" value="{{ old('title') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="manage_description">Descripcion *</label>
                                    <textarea class="form-control" id="manage_description" name="description" rows="3" required>{{ old('description') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="manage_ingredients">Ingredientes *</label>
                                    <textarea class="form-control" id="manage_ingredients" name="ingredients" rows="6" required>{{ old('ingredients') }}</textarea>
                                    <small class="form-text text-muted">Separar cada ingrediente con una nueva linea.</small>
                                </div>

                                <div class="form-group mb-0">
                                    <label for="manage_instructions">Pasos de preparacion *</label>
                                    <textarea class="form-control" id="manage_instructions" name="instructions" rows="6" required>{{ old('instructions') }}</textarea>
                                    <small class="form-text text-muted">Separar cada paso con una nueva linea.</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="recipe-manage-shell">
                                <div class="recipe-manage-section-title">
                                    <i class="fas fa-sliders-h"></i> Configuracion y multimedia
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="manage_preparation_time">Tiempo de preparacion (minutos) *</label>
                                            <input type="number" class="form-control" id="manage_preparation_time" name="preparation_time" min="1" value="{{ old('preparation_time') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="manage_difficulty">Dificultad *</label>
                                            <select class="custom-select" id="manage_difficulty" name="difficulty" required>
                                                <option value="">Seleccione...</option>
                                                <option value="Fácil" {{ old('difficulty') === 'Fácil' ? 'selected' : '' }}>Fácil</option>
                                                <option value="Media" {{ old('difficulty') === 'Media' ? 'selected' : '' }}>Media</option>
                                                <option value="Difícil" {{ old('difficulty') === 'Difícil' ? 'selected' : '' }}>Difícil</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="manage_category_id">Categoria *</label>
                                            <select class="custom-select" id="manage_category_id" name="category_id" required>
                                                <option value="">Seleccione una categoria</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ (string) old('category_id') === (string) $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="manage_subcategory_id">Subcategoria</label>
                                            <select class="custom-select" id="manage_subcategory_id" name="subcategory_id">
                                                <option value="">Seleccione una subcategoria</option>
                                                @foreach($categories as $category)
                                                    @foreach($category->subcategories as $subcategory)
                                                        <option value="{{ $subcategory->id }}" data-category="{{ $category->id }}" {{ (string) old('subcategory_id') === (string) $subcategory->id ? 'selected' : '' }}>
                                                            {{ $subcategory->name }}
                                                        </option>
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $selectedBrand = old('brand', '');
                                    $isCustomBrand = filled($selectedBrand) && !collect($brands)->contains($selectedBrand);
                                @endphp

                                <div class="form-group">
                                    <label for="manage_brand">Marca</label>
                                    <input type="hidden" id="manage_brand" name="brand" value="{{ old('brand') }}">
                                    <div class="brand-chip-group" data-target="manage_brand">
                                        <button type="button" class="brand-chip {{ old('brand', '') === '' ? 'active' : '' }}" data-value="">Sin marca</button>
                                        @foreach($brands as $brand)
                                            <button type="button" class="brand-chip {{ old('brand') === $brand ? 'active' : '' }}" data-value="{{ $brand }}">
                                                {{ $brand }}
                                            </button>
                                        @endforeach
                                        <button type="button" class="brand-chip {{ $isCustomBrand ? 'active' : '' }}" data-value="__other__">
                                            Otra
                                        </button>
                                    </div>
                                    <div class="brand-custom-wrap{{ $isCustomBrand ? ' is-visible' : '' }}" id="manageBrandCustomWrap">
                                        <input
                                            type="text"
                                            class="form-control mt-3"
                                            id="manage_brand_custom"
                                            value="{{ $isCustomBrand ? $selectedBrand : '' }}"
                                            placeholder="Escribe la marca">
                                    </div>
                                </div>

                                @foreach ([
                                    'dish_type' => 'Tipo de platillo',
                                    'daily_category' => 'Para todos los dias',
                                    'special_occasion' => 'Ocasion especial',
                                    'baking_category' => 'Reposteria y panaderia',
                                    'seasonality' => 'Temporalidad',
                                    'preparation_method' => 'Metodos de preparacion',
                                ] as $field => $label)
                                    <div class="form-group">
                                        <label for="manage_{{ $field }}">{{ $label }}</label>
                                        <select class="custom-select" id="manage_{{ $field }}" name="{{ $field }}">
                                            <option value="">Seleccione una opcion</option>
                                            @foreach($filterOptions[$field] as $option)
                                                <option value="{{ $option }}" {{ old($field) === $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach

                                <div class="manage-current-media" id="manageCurrentImageWrap">
                                    <label>Imagen actual</label>
                                    <div class="manage-current-media-frame">
                                        <img id="manageCurrentImage" alt="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="manage_image">Imagen de la receta</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="manage_image" name="image" accept="image/*">
                                        <label class="custom-file-label" for="manage_image" data-default-label="Seleccionar imagen...">Seleccionar imagen...</label>
                                    </div>
                                </div>

                                <div class="manage-current-media" id="manageCurrentVideoWrap">
                                    <label>Video actual</label>
                                    <div class="manage-current-media-frame" id="manageCurrentVideo"></div>
                                </div>

                                <div class="form-group">
                                    <label for="manage_video">Video de la receta</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="manage_video" name="video" accept="video/*">
                                        <label class="custom-file-label" for="manage_video" data-default-label="Seleccionar video...">Seleccionar video...</label>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <label for="manage_video_link">o URL de video</label>
                                    <input type="url" class="form-control" id="manage_video_link" name="video_link" value="{{ old('video_link') }}" placeholder="https://...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn view-recipe-btn manage-submit-btn" id="recipeManageSubmit">
                        <i class="fas fa-save mr-1"></i> Guardar receta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
