@php
    $hoverEmbedUrl = null;
    if (!empty($recipe->video_embed_url)) {
        $separator = str_contains($recipe->video_embed_url, '?') ? '&' : '?';
        $hoverEmbedUrl = $recipe->video_embed_url . $separator . 'autoplay=1&mute=1&controls=0&playsinline=1';
    }
    $hoverDirectVideoUrl = $recipe->video_direct_url ?? $recipe->video_url ?? null;
@endphp

<div class="card h-100 recipe-card">
    <div class="image-wrapper">
        @if(!empty($recipe->image_url))
            <img src="{{ $recipe->image_url }}" alt="{{ $recipe->recipe_title }}">
        @else
            <div class="img-placeholder text-center">
                <i class="fas fa-image fa-3x" style="color: #F28241;"></i>
                <p class="mt-2 mb-0 small">Sin imagen</p>
            </div>
        @endif

        @if($hoverDirectVideoUrl)
            <video class="recipe-video-preview" muted playsinline preload="metadata">
                <source src="{{ $hoverDirectVideoUrl }}" type="video/mp4">
            </video>
        @elseif($hoverEmbedUrl)
            <div class="recipe-embed-preview">
                <iframe
                    data-src="{{ $hoverEmbedUrl }}"
                    title="Vista previa de {{ $recipe->recipe_title }}"
                    allow="autoplay; encrypted-media; picture-in-picture"
                    allowfullscreen
                    referrerpolicy="strict-origin-when-cross-origin"></iframe>
            </div>
        @endif

        @auth
            @php $isFav = $recipe->is_favorite ?? false; @endphp
            <form method="POST"
                  action="{{ route('recipes.toggle-favorite', $recipe->id) }}"
                  class="btn-favorite-form"
                  style="position:absolute;top:0.5rem;right:0.5rem;">
                @csrf
                <button type="submit"
                        class="btn-favorite {{ $isFav ? 'active' : '' }}"
                        title="{{ $isFav ? 'Quitar de favoritos' : 'Agregar a favoritos' }}">
                    <i class="fas fa-heart"></i>
                </button>
            </form>
        @endauth
    </div>

    <div class="card-body">
        <h5 class="card-title">{{ \Illuminate\Support\Str::limit($recipe->recipe_title, 48) }}</h5>
        <p class="card-text text-muted">{{ \Illuminate\Support\Str::limit($recipe->recipe_description ?? '', 90) }}</p>
        <p class="card-text recipe-metrics mb-0">
            <small>
                {{ $recipe->preparation_time }} min
                &middot; {{ $recipe->difficulty }}
                &middot; {{ optional($recipe->category)->name ?? 'Sin categoría' }}
            </small>
        </p>
    </div>

    <div class="card-footer text-center" style="background:transparent;border-top:1px solid #f5dfcf;padding:0.75rem 1rem;">
        <button type="button"
                class="btn btn-sm view-recipe-btn"
                data-recipe-id="{{ $recipe->id }}">
            <i class="fas fa-eye mr-1"></i> Ver receta
        </button>
    </div>
</div>