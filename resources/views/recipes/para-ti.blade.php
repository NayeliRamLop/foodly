@extends('adminlte::page')

@section('title', 'Para Ti - Cocina con Gusto')

@section('content_header')
@stop

@section('css')
<style>
    .content {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    .for-you-feed {
        height: calc(100vh - 90px);
        overflow-y: auto;
        scroll-snap-type: y mandatory;
    }

    .for-you-feed::-webkit-scrollbar {
        width: 6px;
    }

    .for-you-feed::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.25);
        border-radius: 999px;
    }

    .for-you-item {
        min-height: calc(100vh - 90px);
        scroll-snap-align: start;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 14px;
        padding: 8px 8px;
    }

    .for-you-video-box {
        width: min(460px, 92vw);
        height: min(84vh, 900px);
        border-radius: 16px;
        overflow: hidden;
        background: #060606;
        box-shadow: 0 16px 36px rgba(0,0,0,0.5);
        position: relative;
    }

    .for-you-video,
    .for-you-embed {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border: 0;
        background: #000;
    }

    .for-you-side {
        display: flex;
        flex-direction: column;
        gap: 12px;
        align-self: flex-end;
        padding-bottom: 20px;
    }

    .for-you-btn {
        width: 56px;
        border: 0;
        background: transparent;
        color: #F28241;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        cursor: pointer;
    }

    .for-you-bubble {
        width: 50px;
        height: 50px;
        border-radius: 999px;
        background: rgba(255,255,255,0.82);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        backdrop-filter: blur(5px);
    }

    .for-you-profile {
        width: 50px;
        height: 50px;
        border-radius: 999px;
        object-fit: cover;
        border: 2px solid rgba(255,255,255,0.85);
    }

    .for-you-like.active {
        color: #F28241;
    }

    .for-you-count {
        font-size: 0.78rem;
        font-weight: 700;
        color: #F28241;
    }

    .for-you-profile-wrap {
        position: relative;
        display: inline-flex;
    }

    .follow-plus {
        position: absolute;
        right: -2px;
        bottom: -2px;
        width: 18px;
        height: 18px;
        border-radius: 999px;
        background: #F28241;
        color: #fff;
        font-size: 0.85rem;
        line-height: 18px;
        text-align: center;
        border: 1px solid #fff;
        font-weight: 700;
    }

    .for-you-comments-panel {
        position: absolute;
        left: 10px;
        right: 10px;
        bottom: 10px;
        max-height: 44%;
        overflow-y: auto;
        background: rgba(255,255,255,0.95);
        border-radius: 12px;
        border: 1px solid #f1c29c;
        padding: 0.6rem;
        display: none;
    }

    .for-you-comments-panel.open {
        display: block;
    }

    .for-you-comment-item {
        border: 1px solid #f1d4bf;
        border-radius: 8px;
        padding: 0.45rem 0.55rem;
        margin-bottom: 0.45rem;
        background: #fff;
    }

    .for-you-rating-picker {
        display: flex;
        gap: 0.3rem;
    }

    .for-you-star {
        border: 1px solid #f1c29c;
        background: #fff5ec;
        color: #c9783e;
        border-radius: 6px;
        padding: 0.1rem 0.35rem;
        cursor: pointer;
    }

    .for-you-star.active {
        background: #F28241;
        color: #fff;
    }

    @media (max-width: 768px) {
        .for-you-feed {
            height: calc(100vh - 75px);
        }

        .for-you-item {
            min-height: calc(100vh - 75px);
            gap: 8px;
        }

        .for-you-video-box {
            width: 100%;
            height: 78vh;
        }

        .for-you-bubble,
        .for-you-profile {
            width: 44px;
            height: 44px;
        }
    }
</style>
@stop

@section('content')
<div class="for-you-feed" id="forYouFeed">
    @forelse($recipes as $recipe)
        @php
            $embedUrl = null;
            if ($recipe->video_embed_url) {
                $separator = str_contains($recipe->video_embed_url, '?') ? '&' : '?';
                $embedUrl = $recipe->video_embed_url . $separator . 'autoplay=1&mute=0&controls=1&playsinline=1';
            }
            $directVideoUrl = $recipe->video_direct_url;
        @endphp

        <article class="for-you-item" data-recipe-id="{{ $recipe->id }}">
            <div class="for-you-video-box">
                @if($recipe->video)
                    <video class="for-you-video" controls playsinline preload="metadata">
                        <source src="{{ asset('storage/'.$recipe->video) }}" type="video/mp4">
                    </video>
                @elseif($directVideoUrl)
                    <video class="for-you-video" controls playsinline preload="metadata">
                        <source src="{{ $directVideoUrl }}" type="video/mp4">
                    </video>
                @elseif($embedUrl)
                    <iframe
                        class="for-you-embed"
                        data-src="{{ $embedUrl }}"
                        src=""
                        allow="autoplay; encrypted-media; picture-in-picture"
                        allowfullscreen
                        referrerpolicy="strict-origin-when-cross-origin"></iframe>
                @endif

                <div class="for-you-comments-panel" id="commentsPanel{{ $recipe->id }}">
                    <div class="small font-weight-bold mb-2" style="color:#F28241;">Comentarios</div>
                    <div class="for-you-comments-list mb-2" id="commentsList{{ $recipe->id }}">
                        <div class="text-muted small">Sin comentarios todavía.</div>
                    </div>
                    <div class="for-you-comment-form" data-recipe-id="{{ $recipe->id }}" data-rating="5">
                        <div class="for-you-rating-picker mb-2">
                            @for($r = 1; $r <= 5; $r++)
                                <button type="button" class="for-you-star {{ $r === 5 ? 'active' : '' }}" data-rating="{{ $r }}">★</button>
                            @endfor
                        </div>
                        <textarea class="form-control form-control-sm for-you-comment-input" rows="2" placeholder="Escribe un comentario..."></textarea>
                        <button type="button" class="btn btn-sm mt-2 submit-for-you-comment" style="background:#F28241;color:#fff;">Enviar</button>
                    </div>
                </div>
            </div>

            <div class="for-you-side">
                @if($recipe->user)
                    <a href="{{ route('profile.public', $recipe->user->id) }}" class="for-you-btn" title="Ver perfil">
                        <span class="for-you-profile-wrap">
                            @if(!empty($recipe->user->avatar))
                                <img class="for-you-profile" src="{{ asset('storage/'.$recipe->user->avatar) }}" alt="{{ $recipe->user->name }}">
                            @else
                                <span class="for-you-bubble">
                                    <i class="fas fa-user"></i>
                                </span>
                            @endif
                            @if(!$recipe->is_owner && !$recipe->is_following_author)
                                <button type="button" class="follow-plus btn-follow-author" data-user-id="{{ $recipe->user->id }}">+</button>
                            @endif
                        </span>
                        <span class="for-you-count">Perfil</span>
                    </a>
                @endif

                <button type="button" class="for-you-btn btn-for-you-like" data-recipe-id="{{ $recipe->id }}">
                    <span class="for-you-bubble">
                        <i class="fas fa-heart for-you-like {{ $recipe->is_favorite ? 'active' : '' }}"></i>
                    </span>
                    <span class="for-you-count for-you-like-count">{{ $recipe->favorited_by_count }}</span>
                </button>

                <button type="button" class="for-you-btn btn-toggle-comments" data-recipe-id="{{ $recipe->id }}" title="Comentarios">
                    <span class="for-you-bubble">
                        <i class="fas fa-comment-dots"></i>
                    </span>
                    <span class="for-you-count for-you-comments-count">{{ $recipe->comments_count }}</span>
                </button>
            </div>
        </article>
    @empty
        <div class="alert alert-info text-center mt-4">
            No hay recetas con video para mostrar por ahora.
        </div>
    @endforelse
</div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        const feed = document.getElementById('forYouFeed');
        if (feed) {
            const items = Array.from(feed.querySelectorAll('.for-you-item'));

                const activate = (item) => {
                    const video = item.querySelector('.for-you-video');
                    const embed = item.querySelector('.for-you-embed');

                    if (video) {
                        video.muted = false;
                        video.play().catch(() => {});
                    }

                if (embed && !embed.getAttribute('src')) {
                    const src = embed.getAttribute('data-src');
                    if (src) {
                        embed.setAttribute('src', src);
                    }
                }
            };

            const deactivate = (item) => {
                const video = item.querySelector('.for-you-video');
                const embed = item.querySelector('.for-you-embed');

                if (video) {
                    video.pause();
                }

                if (embed) {
                    embed.setAttribute('src', '');
                }
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting && entry.intersectionRatio >= 0.65) {
                        activate(entry.target);
                    } else {
                        deactivate(entry.target);
                    }
                });
            }, {
                root: feed,
                threshold: [0.2, 0.65, 0.9]
            });

            items.forEach((item) => observer.observe(item));
            if (items.length) {
                activate(items[0]);
            }
        }

        const csrf = $('meta[name="csrf-token"]').attr('content');

        $(document).on('click', '.btn-for-you-like', function() {
            const btn = $(this);
            const recipeId = btn.data('recipe-id');
            const icon = btn.find('.for-you-like');
            const count = btn.find('.for-you-like-count');

            $.ajax({
                url: "{{ url('favorites/toggle') }}/" + recipeId,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                success: function(resp) {
                    if (!resp.success) return;
                    const current = parseInt(count.text(), 10) || 0;
                    if (resp.is_favorite) {
                        icon.addClass('active');
                        count.text(current + 1);
                    } else {
                        icon.removeClass('active');
                        count.text(Math.max(0, current - 1));
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        window.location.href = "{{ route('login') }}";
                    }
                }
            });
        });

        const renderComments = (comments) => {
            if (!comments.length) {
                return '<div class="text-muted small">Sin comentarios todavía.</div>';
            }

            return comments.map((comment) => `
                <div class="for-you-comment-item">
                    <div class="small text-muted d-flex justify-content-between">
                        <span>${comment.user}</span>
                        <span>${comment.created_at}</span>
                    </div>
                    <div style="color:#F28241;">${'★'.repeat(comment.rating)}${'☆'.repeat(5 - comment.rating)}</div>
                    <div>${comment.comment}</div>
                </div>
            `).join('');
        };

        $(document).on('click', '.btn-toggle-comments', function() {
            const button = $(this);
            const recipeId = button.data('recipe-id');
            const panel = $('#commentsPanel' + recipeId);
            const list = $('#commentsList' + recipeId);

            const isOpen = panel.hasClass('open');
            $('.for-you-comments-panel').removeClass('open');
            if (isOpen) {
                return;
            }

            panel.addClass('open');
            list.html('<div class="text-muted small">Cargando comentarios...</div>');

            $.ajax({
                url: "{{ route('recipes.show', ':id') }}".replace(':id', recipeId),
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrf
                },
                success: function(resp) {
                    list.html(renderComments(resp.comments || []));
                },
                error: function() {
                    list.html('<div class="text-danger small">No se pudieron cargar los comentarios.</div>');
                }
            });
        });

        $(document).on('click', '.for-you-star', function() {
            const button = $(this);
            const rating = parseInt(button.data('rating'), 10) || 5;
            const form = button.closest('.for-you-comment-form');
            form.attr('data-rating', rating);
            form.find('.for-you-star').each(function() {
                const star = $(this);
                star.toggleClass('active', (parseInt(star.data('rating'), 10) || 0) <= rating);
            });
        });

        $(document).on('click', '.submit-for-you-comment', function() {
            const btn = $(this);
            const form = btn.closest('.for-you-comment-form');
            const recipeId = form.data('recipe-id');
            const rating = parseInt(form.attr('data-rating'), 10) || 5;
            const comment = form.find('.for-you-comment-input').val().trim();
            const list = $('#commentsList' + recipeId);
            const count = form.closest('.for-you-item').find('.for-you-comments-count');

            if (!comment) return;

            $.ajax({
                url: "{{ route('recipes.comments', ':id') }}".replace(':id', recipeId),
                method: 'POST',
                data: { rating: rating, comment: comment },
                headers: {
                    'X-CSRF-TOKEN': csrf
                },
                success: function(resp) {
                    const current = parseInt(count.text(), 10) || 0;
                    count.text(current + 1);
                    form.find('.for-you-comment-input').val('');

                    const itemHtml = `
                        <div class="for-you-comment-item">
                            <div class="small text-muted d-flex justify-content-between">
                                <span>${resp.comment.user}</span>
                                <span>${resp.comment.created_at}</span>
                            </div>
                            <div style="color:#F28241;">${'★'.repeat(resp.comment.rating)}${'☆'.repeat(5 - resp.comment.rating)}</div>
                            <div>${resp.comment.comment}</div>
                        </div>
                    `;
                    if (list.find('.for-you-comment-item').length === 0) {
                        list.html(itemHtml);
                    } else {
                        list.prepend(itemHtml);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        window.location.href = "{{ route('login') }}";
                    }
                }
            });
        });

        $(document).on('click', '.btn-follow-author', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const button = $(this);
            const userId = button.data('user-id');

            $.ajax({
                url: "{{ route('profile.follow', ':id') }}".replace(':id', userId),
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf
                },
                success: function() {
                    button.remove();
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        window.location.href = "{{ route('login') }}";
                    }
                }
            });
        });
    });
</script>
@stop
