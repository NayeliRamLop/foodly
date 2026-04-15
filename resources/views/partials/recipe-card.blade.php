<div class="card h-100 recipe-card">
    <div class="image-wrapper">
        @if($recipe->image)
            <img src="{{ asset($recipe->image) }}" class="img-fluid" alt="{{ $recipe->recipe_title }}">
        @else
            <div class="text-center">
                <i class="fas fa-image fa-3x" style="color: #F28241;"></i>
                <p class="mt-2 mb-0" style="font-size: 1rem;">Sin imagen</p>
            </div>
        @endif
    </div>
    <div class="card-body">
        <h5 class="card-title">{{ \Illuminate\Support\Str::limit($recipe->recipe_title, 48) }}</h5>
        <p class="card-text text-muted">{{ \Illuminate\Support\Str::limit($recipe->recipe_description, 100) }}</p>
        <p class="card-text recipe-metrics mb-0">
            <small>{{ $recipe->favorited_by_count ?? 0 }} favoritos • {{ $recipe->comments_count ?? 0 }} comentarios • {{ number_format((float) ($recipe->avg_rating ?? 0), 1) }}★</small>
        </p>
    </div>
    <div class="card-footer">
        <a class="btn btn-sm view-recipe-btn" href="#" data-recipe-id="{{ $recipe->id }}">
            <i class="fas fa-eye mr-1"></i> Ver
        </a>
    </div>
</div>
