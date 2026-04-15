@extends('adminlte::page')

@section('title', 'Para Ti - Cocina con Gusto')

@section('content_header')
@stop

@section('css')
<style>
    body {
        background-image: url('/images/fondo-04.jpg');
        background-size: cover;
        background-attachment: fixed;
        background-position: center;
    }
    .wrapper,
    .content-wrapper {
        background: transparent !important;
    }

    .content {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    .for-you-wrap {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 57px);
        overflow: hidden;
    }

    .for-you-header {
        flex-shrink: 0;
        padding: 0.55rem 1rem 0.35rem;
        text-align: center;
    }

    .for-you-header h2 {
        font-size: 1.15rem;
        font-weight: 800;
        color: #fff;
        text-shadow: 0 2px 8px rgba(0,0,0,0.45);
        margin: 0;
        letter-spacing: 0.02em;
    }

    .for-you-header h2 i {
        color: #F28241;
    }

    .for-you-player {
        flex: 1 1 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        overflow: hidden;
        padding: 0 0.5rem 0.5rem;
    }

    .for-you-video-box {
        width: min(400px, 88vw);
        height: min(80vh, 840px);
        border-radius: 16px;
        overflow: hidden;
        background: #060606;
        box-shadow: 0 16px 36px rgba(0,0,0,0.5);
        position: relative;
        flex-shrink: 0;
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
        padding-bottom: 16px;
        padding-left: 6px;
        flex-shrink: 0;
    }

    .for-you-btn {
        width: 52px;
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
        width: 48px;
        height: 48px;
        border-radius: 999px;
        background: rgba(255,255,255,0.82);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        backdrop-filter: blur(5px);
    }

    .for-you-profile-img {
        width: 48px;
        height: 48px;
        border-radius: 999px;
        object-fit: cover;
        border: 2px solid rgba(255,255,255,0.85);
        display: block;
    }

    .for-you-like.active {
        color: #F28241;
    }

    .for-you-count {
        font-size: 0.75rem;
        font-weight: 700;
        color: #fff;
        text-shadow: 0 1px 4px rgba(0,0,0,0.5);
    }

    .for-you-profile-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .follow-plus {
        position: absolute;
        right: -3px;
        bottom: -3px;
        width: 18px;
        height: 18px;
        border-radius: 999px;
        background: #F28241;
        color: #fff;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #fff;
        font-weight: 700;
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }

    /* Panel de comentarios a la derecha */
    .for-you-comments-panel {
        width: 280px;
        max-width: 30vw;
        height: min(80vh, 840px);
        display: none;
        flex-direction: column;
        background: rgba(255,255,255,0.97);
        border-radius: 14px;
        border: 1px solid #f1c29c;
        margin-left: 8px;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: 0 8px 24px rgba(0,0,0,0.18);
    }

    .for-you-comments-panel.open {
        display: flex;
    }

    .for-you-comments-header {
        font-size: 0.9rem;
        font-weight: 700;
        color: #F28241;
        padding: 0.6rem 0.75rem;
        border-bottom: 1px solid #f1c29c;
        flex-shrink: 0;
    }

    .for-you-comments-list {
        flex: 1 1 auto;
        overflow-y: auto;
        padding: 0.5rem 0.6rem;
    }

    .for-you-comment-item {
        border: 1px solid #f1d4bf;
        border-radius: 8px;
        padding: 0.4rem 0.5rem;
        margin-bottom: 0.4rem;
        background: #fff;
        font-size: 0.82rem;
    }

    .for-you-comment-form-area {
        flex-shrink: 0;
        padding: 0.5rem 0.6rem;
        border-top: 1px solid #f1c29c;
    }

    .for-you-rating-picker {
        display: flex;
        gap: 0.25rem;
        margin-bottom: 0.35rem;
    }

    .for-you-star {
        border: 1px solid #f1c29c;
        background: #fff5ec;
        color: #c9783e;
        border-radius: 6px;
        padding: 0.1rem 0.3rem;
        cursor: pointer;
        font-size: 0.85rem;
    }

    .for-you-star.active {
        background: #F28241;
        color: #fff;
    }

    /* Botones de nav circular */
    .for-you-nav {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding-left: 6px;
        align-self: center;
        flex-shrink: 0;
    }

    .for-you-nav-btn {
        width: 40px;
        height: 40px;
        border-radius: 999px;
        background: rgba(255,255,255,0.75);
        border: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: #F28241;
        cursor: pointer;
        backdrop-filter: blur(4px);
        transition: background 0.2s;
    }

    .for-you-nav-btn:hover {
        background: rgba(255,255,255,0.95);
    }

    @media (max-width: 768px) {
        .for-you-wrap {
            height: calc(100vh - 50px);
        }
        .for-you-video-box {
            width: 92vw;
            height: 72vh;
        }
        .for-you-comments-panel {
            display: none !important;
        }
        .for-you-nav {
            display: none;
        }
    }
</style>
@stop

@section('content')
@php
    $recipesData = $recipes->map(function($recipe) {
        return [
            'id'                  => $recipe->id,
            'recipe_title'        => $recipe->recipe_title,
            'user'                => $recipe->user ? [
                'id'         => $recipe->user->id,
                'name'       => $recipe->user->name,
                'avatar_url' => $recipe->user->avatar_url ?? null,
            ] : null,
            'video'               => $recipe->video ? asset($recipe->video) : null,
            'video_direct_url'    => $recipe->video_direct_url,
            'video_embed_url'     => $recipe->video_embed_url,
            'is_favorite'         => (bool) ($recipe->is_favorite ?? false),
            'is_owner'            => (bool) ($recipe->is_owner ?? false),
            'is_following_author' => (bool) ($recipe->is_following_author ?? false),
            'favorited_by_count'  => (int) ($recipe->favorited_by_count ?? 0),
            'comments_count'      => (int) ($recipe->comments_count ?? 0),
        ];
    })->values()->all();
@endphp

<div class="for-you-wrap">
    <div class="for-you-header">
        <h2>
            <i class="fas fa-play-circle"></i>
            Para Ti &mdash; Videos que pueden gustarte
        </h2>
    </div>

    @if(count($recipesData) === 0)
        <div class="d-flex align-items-center justify-content-center flex-grow-1">
            <div class="alert alert-info text-center">
                No hay recetas con video para mostrar por ahora.
            </div>
        </div>
    @else
        <div class="for-you-player" id="forYouPlayer">
            <!-- Botones de navegación (prev) -->
            <div class="for-you-nav">
                <button type="button" class="for-you-nav-btn" id="btnPrev" title="Anterior">
                    <i class="fas fa-chevron-up"></i>
                </button>
                <button type="button" class="for-you-nav-btn" id="btnNext" title="Siguiente">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>

            <!-- Caja de video -->
            <div class="for-you-video-box" id="forYouVideoBox">
                <!-- JS rellena el video -->
            </div>

            <!-- Bloque lateral pegado al video -->
            <div class="for-you-side" id="forYouSide">
                <!-- JS rellena el perfil/like/comentarios -->
            </div>

            <!-- Panel de comentarios a la derecha -->
            <div class="for-you-comments-panel" id="forYouCommentsPanel">
                <div class="for-you-comments-header">
                    <i class="fas fa-comment-dots mr-1"></i> Comentarios
                </div>
                <div class="for-you-comments-list" id="forYouCommentsList">
                    <div class="text-muted small text-center py-3">Sin comentarios todavía.</div>
                </div>
                <div class="for-you-comment-form-area" id="forYouCommentForm">
                    <div class="for-you-rating-picker mb-1" id="forYouRatingPicker">
                        @for($r = 1; $r <= 5; $r++)
                            <button type="button" class="for-you-star {{ $r === 5 ? 'active' : '' }}" data-rating="{{ $r }}">★</button>
                        @endfor
                    </div>
                    <textarea class="form-control form-control-sm mb-1" id="forYouCommentInput" rows="2" placeholder="Escribe un comentario..."></textarea>
                    <button type="button" class="btn btn-sm w-100" id="btnSubmitComment" style="background:#F28241;color:#fff;">Enviar</button>
                    <div id="forYouCommentError" class="text-danger small mt-1" style="display:none;"></div>
                </div>
            </div>
        </div>
    @endif
</div>
@stop

@section('js')
<script>
$(document).ready(function () {
    const recipes = @json($recipesData);
    if (!recipes.length) return;

    let currentIndex = 0;
    let currentRating = 5;
    let commentsOpen = false;
    const csrf = $('meta[name="csrf-token"]').attr('content');

    const videoBox      = document.getElementById('forYouVideoBox');
    const sideBox       = document.getElementById('forYouSide');
    const commentsPanel = document.getElementById('forYouCommentsPanel');
    const commentsList  = document.getElementById('forYouCommentsList');
    const commentInput  = document.getElementById('forYouCommentInput');
    const commentError  = document.getElementById('forYouCommentError');
    const btnPrev       = document.getElementById('btnPrev');
    const btnNext       = document.getElementById('btnNext');

    // ── helpers ─────────────────────────────────────────────────────────────
    const escHtml = (s) => String(s ?? '')
        .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;').replace(/'/g,'&#039;');

    const stars = (n) =>
        '★'.repeat(Math.max(0,Math.min(5,Number(n)||0))) +
        '☆'.repeat(5 - Math.max(0,Math.min(5,Number(n)||0)));

    // ── cargar receta ────────────────────────────────────────────────────────
    function loadRecipe(index) {
        const recipe = recipes[index];

        // ── video ────────────────────────────────────────────────────────────
        stopCurrentMedia();
        let mediaHtml = '';
        const embedBase = recipe.video_embed_url;
        const directUrl = recipe.video_direct_url || recipe.video;

        if (directUrl) {
            mediaHtml = `<video class="for-you-video" playsinline preload="metadata" id="fyVideo" loop>
                            <source src="${escHtml(directUrl)}" type="video/mp4">
                         </video>`;
        } else if (embedBase) {
            const sep = embedBase.includes('?') ? '&' : '?';
            const src = embedBase + sep + 'autoplay=1&mute=0&controls=1&playsinline=1';
            mediaHtml = `<iframe class="for-you-embed" id="fyEmbed"
                            src="${escHtml(src)}"
                            allow="autoplay; encrypted-media; picture-in-picture"
                            allowfullscreen
                            referrerpolicy="strict-origin-when-cross-origin"></iframe>`;
        }

        videoBox.innerHTML = mediaHtml;

        // Autoplay con audio; fallback a muted si el browser lo bloquea
        const vid = videoBox.querySelector('.for-you-video');
        if (vid) {
            vid.muted = false;
            vid.play().catch(() => {
                vid.muted = true;
                vid.play().catch(() => {});
            });
        }

        // ── barra lateral ────────────────────────────────────────────────────
        const user = recipe.user;
        let profileHtml = '';
        if (user) {
            const avatarHtml = user.avatar_url
                ? `<img class="for-you-profile-img" src="${escHtml(user.avatar_url)}" alt="${escHtml(user.name)}">`
                : `<span class="for-you-bubble"><i class="fas fa-user"></i></span>`;
            const followBtn = (!recipe.is_owner && !recipe.is_following_author)
                ? `<button type="button" class="follow-plus btn-follow-author" data-user-id="${user.id}" title="Seguir">+</button>`
                : '';
            profileHtml = `
                <a href="/perfil/${user.id}" class="for-you-btn" title="Ver perfil">
                    <span class="for-you-profile-wrap">
                        ${avatarHtml}
                        ${followBtn}
                    </span>
                    <span class="for-you-count">${escHtml(user.name.split(' ')[0])}</span>
                </a>`;
        }

        sideBox.innerHTML = `
            ${profileHtml}
            <button type="button" class="for-you-btn" id="btnLike" data-recipe-id="${recipe.id}">
                <span class="for-you-bubble">
                    <i class="fas fa-heart for-you-like ${recipe.is_favorite ? 'active' : ''}"></i>
                </span>
                <span class="for-you-count" id="likeCount">${recipe.favorited_by_count}</span>
            </button>
            <button type="button" class="for-you-btn" id="btnComments" data-recipe-id="${recipe.id}" title="Comentarios">
                <span class="for-you-bubble">
                    <i class="fas fa-comment-dots"></i>
                </span>
                <span class="for-you-count" id="commentsCount">${recipe.comments_count}</span>
            </button>
        `;

        // ── comentarios (recargar si estaban abiertos) ───────────────────────
        if (commentsOpen) {
            loadComments(recipe.id);
        }
    }

    function stopCurrentMedia() {
        const vid = videoBox.querySelector('.for-you-video');
        if (vid) { vid.pause(); vid.src = ''; }
        const emb = videoBox.querySelector('.for-you-embed');
        if (emb) { emb.src = ''; }
    }

    // ── navegación circular ──────────────────────────────────────────────────
    function goTo(index) {
        currentIndex = ((index % recipes.length) + recipes.length) % recipes.length;
        loadRecipe(currentIndex);
    }

    btnNext.addEventListener('click', () => goTo(currentIndex + 1));
    btnPrev.addEventListener('click', () => goTo(currentIndex - 1));

    // ── like ─────────────────────────────────────────────────────────────────
    $(document).on('click', '#btnLike', function () {
        const btn = $(this);
        const recipeId = btn.data('recipe-id');
        $.ajax({
            url: "{{ url('favorites/toggle') }}/" + recipeId,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            success: function (resp) {
                if (!resp.success) return;
                const icon = btn.find('.for-you-like');
                const count = btn.find('#likeCount');
                const cur = parseInt(count.text(), 10) || 0;
                if (resp.is_favorite) {
                    icon.addClass('active');
                    count.text(cur + 1);
                    recipes[currentIndex].is_favorite = true;
                    recipes[currentIndex].favorited_by_count = cur + 1;
                } else {
                    icon.removeClass('active');
                    count.text(Math.max(0, cur - 1));
                    recipes[currentIndex].is_favorite = false;
                    recipes[currentIndex].favorited_by_count = Math.max(0, cur - 1);
                }
            },
            error: function (xhr) {
                if (xhr.status === 401) window.location.href = "{{ route('login') }}";
            }
        });
    });

    // ── toggle panel de comentarios ──────────────────────────────────────────
    $(document).on('click', '#btnComments', function () {
        commentsOpen = !commentsOpen;
        if (commentsOpen) {
            commentsPanel.classList.add('open');
            loadComments(recipes[currentIndex].id);
        } else {
            commentsPanel.classList.remove('open');
        }
    });

    // ── cargar comentarios via AJAX ──────────────────────────────────────────
    function loadComments(recipeId) {
        commentsList.innerHTML = '<div class="text-muted small text-center py-2">Cargando...</div>';
        $.ajax({
            url: "{{ route('recipes.show', ':id') }}".replace(':id', recipeId),
            method: 'GET',
            headers: { 'X-CSRF-TOKEN': csrf },
            success: function (resp) {
                renderComments(resp.comments || []);
            },
            error: function () {
                commentsList.innerHTML = '<div class="text-danger small text-center py-2">Error al cargar.</div>';
            }
        });
    }

    function renderComments(comments) {
        if (!comments.length) {
            commentsList.innerHTML = '<div class="text-muted small text-center py-2">Sin comentarios todavía.</div>';
            return;
        }
        commentsList.innerHTML = comments.map(c => `
            <div class="for-you-comment-item">
                <div class="d-flex justify-content-between" style="font-size:0.78rem;">
                    <strong>${escHtml(c.user)}</strong>
                    <span class="text-muted">${escHtml(c.created_at)}</span>
                </div>
                <div style="color:#F28241;font-size:0.78rem;">${stars(c.rating)}</div>
                <div style="font-size:0.82rem;">${escHtml(c.comment)}</div>
            </div>
        `).join('');
    }

    // ── calificación ─────────────────────────────────────────────────────────
    $(document).on('click', '.for-you-star', function () {
        currentRating = parseInt($(this).data('rating'), 10) || 5;
        $('#forYouRatingPicker .for-you-star').each(function () {
            $(this).toggleClass('active', (parseInt($(this).data('rating'),10)||0) <= currentRating);
        });
    });

    // ── enviar comentario ────────────────────────────────────────────────────
    $('#btnSubmitComment').on('click', function () {
        const comment = (commentInput.value || '').trim();
        if (!comment) return;
        const recipeId = recipes[currentIndex].id;
        commentError.style.display = 'none';

        $.ajax({
            url: "{{ route('recipes.comments', ':id') }}".replace(':id', recipeId),
            method: 'POST',
            data: { rating: currentRating, comment: comment },
            headers: { 'X-CSRF-TOKEN': csrf },
            success: function (resp) {
                commentInput.value = '';
                const countEl = document.getElementById('commentsCount');
                if (countEl) countEl.textContent = (parseInt(countEl.textContent,10)||0) + 1;
                recipes[currentIndex].comments_count++;
                const newItem = `
                    <div class="for-you-comment-item">
                        <div class="d-flex justify-content-between" style="font-size:0.78rem;">
                            <strong>${escHtml(resp.comment.user)}</strong>
                            <span class="text-muted">${escHtml(resp.comment.created_at)}</span>
                        </div>
                        <div style="color:#F28241;font-size:0.78rem;">${stars(resp.comment.rating)}</div>
                        <div style="font-size:0.82rem;">${escHtml(resp.comment.comment)}</div>
                    </div>`;
                if (commentsList.querySelector('.text-muted')) {
                    commentsList.innerHTML = newItem;
                } else {
                    commentsList.insertAdjacentHTML('afterbegin', newItem);
                }
            },
            error: function (xhr) {
                if (xhr.status === 401) {
                    window.location.href = "{{ route('login') }}";
                    return;
                }
                const msg = xhr.responseJSON?.errors?.comment?.[0]
                    || xhr.responseJSON?.message
                    || 'Error al enviar el comentario.';
                commentError.textContent = msg;
                commentError.style.display = 'block';
            }
        });
    });

    // ── seguir autor ─────────────────────────────────────────────────────────
    $(document).on('click', '.btn-follow-author', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const btn = $(this);
        const userId = btn.data('user-id');
        $.ajax({
            url: "{{ route('profile.follow', ':id') }}".replace(':id', userId),
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf },
            success: function () { btn.remove(); },
            error: function (xhr) {
                if (xhr.status === 401) window.location.href = "{{ route('login') }}";
            }
        });
    });

    // ── iniciar ──────────────────────────────────────────────────────────────
    loadRecipe(0);
});
</script>
@stop