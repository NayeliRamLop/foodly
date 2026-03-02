@extends('adminlte::page')

@section('title', 'RECETAS - COCINA CON GUSTO')

@section('content_header')
    <h1 class="m-0" style="font-size: 4rem; font-weight: 700; color: #F28241; text-shadow: 2px 2px 4px rgba(200, 200, 200, 0.6); letter-spacing: 0.05em; margin-bottom: 1.5rem;">
        RECETAS
        <a href="{{ route('favorites.index') }}" class="btn btn-outline-danger float-right" style="font-size: 1rem;">
            <i class="fas fa-heart mr-1"></i> Mis Favoritos
        </a>
    </h1>
@stop

@section('content')
<!-- Notificación temporal -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" id="successAlert" style="font-size: 1.1rem;">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" style="font-size: 1.1rem;">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="row recipes-page">
    @foreach($recipes as $recipe)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 recipe-card" data-recipe-id="{{ $recipe->id }}">
            <!-- Contenedor flexible para la imagen -->
            <div class="image-wrapper" style="height: 200px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border-top-left-radius: 12px; border-top-right-radius: 12px; position: relative; overflow: hidden;">
                @if($recipe->image)
                <img src="{{ asset('storage/'.$recipe->image) }}" class="img-fluid" alt="{{ $recipe->recipe_title }}" style="max-height: 100%; max-width: 100%; object-fit: scale-down;">
                @else
                <div class="text-center">
                    <i class="fas fa-image fa-3x" style="color: #F28241;"></i>
                    <p class="mt-2" style="font-size: 1.1rem;">Sin imagen</p>
                </div>
                @endif
                @if($recipe->video)
                <video class="recipe-video-preview" muted playsinline preload="metadata">
                    <source src="{{ asset('storage/'.$recipe->video) }}" type="video/mp4">
                </video>
                @endif
                
                <!-- Corazón de favoritos -->
                <button class="btn-favorite" data-recipe-id="{{ $recipe->id }}" style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.7); border: none; border-radius: 50%; width: 36px; height: 36px; padding: 0; z-index: 10;">
                    <i class="fas fa-heart {{ auth()->check() && auth()->user()->favorites->contains($recipe->id) ? 'text-danger' : 'text-secondary' }}" style="font-size: 1.2rem;"></i>
                </button>
            </div>
            
            <div class="card-body">
                <h5 class="card-title">{{ Str::limit($recipe->recipe_title, 40) }}</h5>
                <p class="card-text text-muted">{{ Str::limit($recipe->recipe_description, 70) }}</p>
            </div>
            
            <!-- Botones de acción simplificados -->
            <div class="card-footer bg-white border-top-0">
                <div class="d-flex justify-content-center">
                    <button class="btn btn-sm view-recipe-btn" data-toggle="modal" data-target="#recipeModal" data-recipe-id="{{ $recipe->id }}">
                        <i class="fas fa-eye mr-1"></i> Ver
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Modal para ver receta completa -->
<div class="modal fade" id="recipeModal" tabindex="-1" role="dialog" aria-labelledby="recipeModalLabel">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #F28241; color: white;">
                <h4 class="modal-title" id="recipeModalLabel" style="font-size: 1.5rem;">
                    <i class="fas fa-utensils mr-2"></i> Receta Completa
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color: white;">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="recipeModalBody" style="font-size: 1.1rem;">
                <!-- Contenido dinámico se cargará aquí -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size: 1.1rem;">
                    <i class="fas fa-times mr-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    body {
        font-size: 1.1rem;
    }
    
    .recipe-card {
        transition: all 0.25s ease;
        border-radius: 16px;
        border: 1px solid #eee;
        box-shadow: 0 12px 24px rgba(0,0,0,0.06);
        overflow: hidden;
        background: var(--bg-soft, #fff6e9); /* same tono claro que top recetas */
        max-width: 100%;
    }
    .recipe-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(var(--primary-rgb, 65,89,29), 0.15);
        border-color: rgba(var(--primary-rgb, 65,89,29), 0.3);
    }
    .recipe-video-preview {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 0.2s ease;
        pointer-events: none;
        background: #000;
    }
    .recipe-card:hover .recipe-video-preview {
        opacity: 1;
    }
    .card-title {
        font-size: 1.3rem;
        margin-bottom: 0.75rem;
        color: var(--primary); /* verde destacado */
    }
    .image-wrapper {
        padding: 0;
    }
    .fade-out {
        animation: fadeOut 2s ease-in-out;
        opacity: 0;
    }
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s;
        font-size: 1.1rem;
    }
    .view-recipe-btn {
        background-color: var(--primary);
        color: #fff;
    }
    .view-recipe-btn:hover {
        background-color: color-mix(in srgb, var(--primary) 85%, black);
    }
    .btn:hover {
        transform: translateY(-2px);
    }
    .card-footer {
        padding: 0.6rem 1rem 1rem;
        background: #fff;
    }
    .card-body {
        min-height: 140px;
    }
    .recipe-card .btn {
        font-size: 0.95rem;
        padding: 0.45rem 0.9rem;
    }
    .recipe-modal-img {
        max-height: 300px;
        width: auto;
        display: block;
        margin: 0 auto;
        border-radius: 8px;
    }
    .recipe-modal-title {
        color: #F28241;
        font-weight: 600;
        margin-bottom: 20px;
    }
    .recipe-section {
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .recipe-section-title {
        color: #F28241;
        font-weight: 600;
        margin-bottom: 15px;
    }
    .btn-favorite {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255,255,255,0.7);
        border: none;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        padding: 0;
        z-index: 10;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-favorite:hover {
        background: rgba(255,255,255,0.9);
    }
    .btn-favorite i {
        font-size: 1.2rem;
        transition: all 0.2s ease;
    }
    .btn-favorite:hover i {
        transform: scale(1.2);
    }
    .comment-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .comment-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 0.6rem 0.8rem;
        border: 1px solid #e9ecef;
    }
    .comment-meta {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.3rem;
    }
    .comment-rating {
        font-size: 0.95rem;
        color: #D2691E;
        margin-bottom: 0.35rem;
    }
    .comment-text {
        font-size: 0.95rem;
        color: #2f2f2f;
    }
    .comment-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
    }
    .comment-filter {
        border: 1px solid #f1c29c;
        background: #fff2e6;
        color: #a85d2d;
        border-radius: 999px;
        padding: 0.2rem 0.7rem;
        font-size: 0.85rem;
        cursor: pointer;
    }
    .comment-filter.active,
    .comment-filter:hover {
        background: #D2691E;
        color: #ffffff;
    }
    .comment-rating-picker {
        display: flex;
        gap: 0.4rem;
    }
    .comment-star {
        border: 1px solid #f1c29c;
        background: #fff2e6;
        color: #d28c57;
        border-radius: 6px;
        font-size: 1.1rem;
        padding: 0.2rem 0.5rem;
        cursor: pointer;
    }
    .comment-star.active,
    .comment-star:hover {
        background: #D2691E;
        color: #ffffff;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        let currentVideoElement = null;
        const profileBaseUrl = "{{ url('/perfil') }}";
        const canRate = @json(auth()->check());
        const canComment = @json(auth()->check());

        // Cargar receta en el modal al hacer clic en "Ver"
        $('.view-recipe-btn').on('click', function() {
            const recipeId = $(this).data('recipe-id');
            const url = "{{ route('recipes.show', ':id') }}".replace(':id', recipeId);
            
            $('#recipeModalBody').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando receta...</p>
                </div>
            `);
            
            $.ajax({
                url: url,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    let modalContent = `
                        <div class="text-center mb-4">
                            ${response.image ? 
                                `<img src="/storage/${response.image}" class="recipe-modal-img img-fluid" alt="${response.recipe_title}">` : 
                                `<div class="text-center py-4" style="background-color: #f8f9fa; border-radius: 8px;">
                                    <i class="fas fa-image fa-5x" style="color: #F28241;"></i>
                                    <p class="mt-2">Sin imagen</p>
                                </div>`
                            }
                        </div>
                        
                        <h3 class="recipe-modal-title">${response.recipe_title}</h3>
                        
                        <div class="recipe-section">
                            <p>${response.recipe_description}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    <i class="fas fa-user-circle mr-1"></i> Creado por: ${response.user ? `<a href="${profileBaseUrl}/${response.user.id}" class="text-decoration-none">${response.user.name} ${response.user.last_name || ''}</a>` : 'Administrador'}
                                </div>
                                <div>
                                    <span class="badge badge-pill mr-2" style="background-color: #5cb85c;">
                                        <i class="fas fa-clock"></i> ${response.preparation_time} min
                                    </span>
                                    <span class="badge badge-pill" style="background-color: #6c757d;">
                                        <i class="fas fa-utensil-spoon"></i> ${response.difficulty}
                                    </span>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $recipes->links() }}
</div>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-4">
                            <span class="badge badge-pill mr-2" style="background-color: #F28241;">
                                <i class="fas fa-tag"></i> ${response.category.name}
                            </span>
                            ${response.subcategory ? 
                                `<span class="badge badge-pill badge-light">
                                    <i class="fas fa-tags"></i> ${response.subcategory.name}
                                </span>` : ''
                            }
                        </div>`;
                    
                    if (response.ingredients) {
                        modalContent += `
                        <div class="recipe-section">
                            <h5 class="recipe-section-title"><i class="fas fa-list-ul mr-1"></i> Ingredientes:</h5>
                            <ul class="pl-3">`;
                        
                        response.ingredients.split('\n').forEach(ingredient => {
                            if (ingredient.trim() !== '') {
                                modalContent += `<li>${ingredient}</li>`;
                            }
                        });
                        
                        modalContent += `</ul></div>`;
                    }
                    
                    if (response.instructions) {
                        modalContent += `
                        <div class="recipe-section">
                            <h5 class="recipe-section-title"><i class="fas fa-list-ol mr-1"></i> Preparación:</h5>
                            <ol class="pl-3">`;
                        
                        response.instructions.split('\n').forEach(step => {
                            if (step.trim() !== '') {
                                modalContent += `<li>${step}</li>`;
                            }
                        });
                        
                        modalContent += `</ol></div>`;
                    }
                    
                    if (response.video) {
                        modalContent += `
                        <div class="recipe-section">
                            <h5 class="recipe-section-title"><i class="fas fa-video mr-1"></i> Video:</h5>
                            <div class="embed-responsive embed-responsive-16by9">
                                <video id="recipeVideo" controls class="w-100 rounded" style="background-color: #f8f9fa;">
                                    <source src="/storage/${response.video}" type="video/mp4">
                                    Tu navegador no soporta el elemento de video.
                                </video>
                            </div>
                        </div>`;
                    }

                    if (response.comments) {
                        const renderComments = (items, filterRating = null) => {
                            const list = filterRating
                                ? items.filter((comment) => comment.rating === filterRating)
                                : items;

                            if (!list.length) {
                                return `<div class="text-muted small">No hay comentarios con ese filtro.</div>`;
                            }

                            return list.map((comment) => `
                                <div class="comment-item" data-rating="${comment.rating}">
                                    <div class="comment-meta">
                                        <span class="comment-user">${comment.user}</span>
                                        <span class="comment-date">${comment.created_at}</span>
                                    </div>
                                    <div class="comment-rating">${'★'.repeat(comment.rating)}${'☆'.repeat(5 - comment.rating)}</div>
                                    <div class="comment-text">${comment.comment}</div>
                                </div>
                            `).join('');
                        };

                        const filters = [5,4,3,2,1].map((rating) => {
                            const count = response.comment_rating_counts[rating] || 0;
                            return `
                                <button type="button" class="comment-filter" data-rating="${rating}">
                                    ${rating} estrellas (${count})
                                </button>
                            `;
                        }).join('');

                        modalContent += `
                        <div class="recipe-section">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <h5 class="recipe-section-title mb-0"><i class="fas fa-comments mr-1"></i> Comentarios</h5>
                                <div class="comment-filters" data-recipe-id="${response.id}">
                                    <button type="button" class="comment-filter active" data-rating="">Todos (${response.comments.length})</button>
                                    ${filters}
                                </div>
                            </div>
                            <div class="comment-list mt-3" data-comments="${encodeURIComponent(JSON.stringify(response.comments))}">
                                ${renderComments(response.comments)}
                            </div>
                        </div>`;
                    }

                    // Comentario y calificacion se muestran arriba

                    if (canRate) {
                        modalContent += `
                        <div class="recipe-section">
                            <h5 class="recipe-section-title"><i class="fas fa-star mr-1"></i> Calificar</h5>
                            <div class="comment-form" data-recipe-id="${response.id}" data-rating="5">
                                <div class="comment-rating-picker">
                                    ${[1,2,3,4,5].map((rating) => `
                                        <button type="button" class="comment-star ${rating === 5 ? 'active' : ''}" data-rating="${rating}">★</button>
                                    `).join('')}
                                </div>
                                <textarea class="form-control comment-input mt-2" rows="2" placeholder="Escribe tu comentario..."></textarea>
                                <button type="button" class="btn btn-primary btn-sm mt-2 submit-comment">Enviar</button>
                            </div>
                        </div>`;
                    }
                    
                    $('#recipeModalBody').html(modalContent);
                    $('#recipeModalLabel').html(`<i class="fas fa-utensils mr-2"></i> ${response.recipe_title}`);
                    
                    if (response.video) {
                        currentVideoElement = document.getElementById('recipeVideo');
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                    $('#recipeModalBody').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle mr-2"></i> 
                            No se pudo cargar la receta. Error: ${xhr.status} - ${xhr.statusText}
                        </div>
                    `);
                }
            });
        });
        
        $('.recipe-card').on('mouseenter', function() {
            const video = $(this).find('.recipe-video-preview').get(0);
            if (!video) return;
            video.currentTime = 0;
            video.play();
            setTimeout(() => {
                if (!video.paused) {
                    video.pause();
                }
            }, 2000);
        });

        $('.recipe-card').on('mouseleave', function() {
            const video = $(this).find('.recipe-video-preview').get(0);
            if (!video) return;
            video.pause();
            video.currentTime = 0;
        });

        // Detener el video cuando se cierra el modal
        $('#recipeModal').on('hidden.bs.modal', function () {
            if (currentVideoElement) {
                currentVideoElement.pause();
                currentVideoElement.currentTime = 0;
                currentVideoElement = null;
            }
        });

        // Se califica al enviar comentario

        $(document).on('click', '.comment-star', function() {
            const button = $(this);
            const container = button.closest('.comment-form');
            const rating = button.data('rating');
            container.attr('data-rating', rating);
            container.find('.comment-star').each(function() {
                const star = $(this);
                star.toggleClass('active', star.data('rating') <= rating);
            });
        });

        $(document).on('click', '.submit-comment', function() {
            const button = $(this);
            const container = button.closest('.comment-form');
            const recipeId = container.data('recipe-id');
            const rating = parseInt(container.attr('data-rating'), 10) || 5;
            const text = container.find('.comment-input').val().trim();

            if (!text) {
                return;
            }

            $.ajax({
                url: "{{ route('recipes.comments', ':id') }}".replace(':id', recipeId),
                method: 'POST',
                data: { rating: rating, comment: text },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    const list = $('#recipeModalBody').find('.comment-list');
                    const item = `
                        <div class="comment-item" data-rating="${response.comment.rating}">
                            <div class="comment-meta">
                                <span class="comment-user">${response.comment.user}</span>
                                <span class="comment-date">${response.comment.created_at}</span>
                            </div>
                            <div class="comment-rating">${'★'.repeat(response.comment.rating)}${'☆'.repeat(5 - response.comment.rating)}</div>
                            <div class="comment-text">${response.comment.comment}</div>
                        </div>
                    `;
                    if (list.length) {
                        list.prepend(item);
                        const raw = list.attr('data-comments');
                        if (raw) {
                            const items = JSON.parse(decodeURIComponent(raw));
                            items.unshift(response.comment);
                            list.attr('data-comments', encodeURIComponent(JSON.stringify(items)));
                        }
                    }
                    container.find('.comment-input').val('');
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        window.location.href = "{{ route('login') }}";
                    }
                }
            });
        });

        $(document).on('click', '.comment-filter', function() {
            const button = $(this);
            const filters = button.closest('.comment-filters');
            const rating = button.data('rating');
            const list = filters.closest('.recipe-section').find('.comment-list');
            const raw = list.attr('data-comments');
            if (!raw) return;
            const items = JSON.parse(decodeURIComponent(raw));

            filters.find('.comment-filter').removeClass('active');
            button.addClass('active');

            const filtered = rating ? items.filter((comment) => comment.rating === rating) : items;
            const html = filtered.length
                ? filtered.map((comment) => `
                    <div class="comment-item" data-rating="${comment.rating}">
                        <div class="comment-meta">
                            <span class="comment-user">${comment.user}</span>
                            <span class="comment-date">${comment.created_at}</span>
                        </div>
                        <div class="comment-rating">${'★'.repeat(comment.rating)}${'☆'.repeat(5 - comment.rating)}</div>
                        <div class="comment-text">${comment.comment}</div>
                    </div>
                `).join('')
                : `<div class="text-muted small">No hay comentarios con ese filtro.</div>`;

            list.html(html);
        });
        
        // Ocultar alerta de éxito
        if ($('#successAlert').length) {
            setTimeout(function() {
                $('#successAlert').addClass('fade-out');
                setTimeout(function() {
                    $('#successAlert').remove();
                }, 2000);
            }, 2000);
        }
        
        // Toggle de favoritos
        $(document).on('click', '.btn-favorite', function(e) {
            e.preventDefault();
            const button = $(this);
            const recipeId = button.data('recipe-id');
            const heartIcon = button.find('i');
            
            $.ajax({
                url: "{{ url('favorites/toggle') }}/" + recipeId,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        heartIcon.toggleClass('text-danger text-secondary');
                        
                        // Si estamos en la vista de favoritos y se quitó de favoritos
                        if (window.location.pathname.includes('favorites') && !response.is_favorite) {
                            button.closest('.col-md-4').fadeOut(300, function() {
                                $(this).remove();
                                if ($('.recipe-card').length === 0) {
                                    location.reload();
                                }
                            });
                        }
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        window.location.href = "{{ route('login') }}";
                    } else {
                        console.error(xhr);
                        alert('Ocurrió un error. Por favor recarga la página.');
                    }
                }
            });
        });
    });
</script>
@stop
