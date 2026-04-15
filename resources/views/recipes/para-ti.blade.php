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

    .content-wrapper > .content {
        background: transparent !important;
    }

    .content {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    .for-you-wrap {
        display: flex;
        flex-direction: column;
        min-height: calc(100vh - 68.19px);
        padding: 0.35rem 0 0.75rem;
    }

    .for-you-header {
        flex-shrink: 0;
        padding: 0.3rem 1rem 0.2rem;
        text-align: center;
    }

    .for-you-header h2 {
        font-size: 1.6rem;
        font-weight: 800;
        color: #F28241;
        margin: 0;
        letter-spacing: 0.04em;
        text-transform: uppercase;
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
        overflow: visible;
        padding: 0.1rem 0.6rem 0.5rem;
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
        padding-left: 14px;
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

    .for-you-comment-actions {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        margin-top: 0.45rem;
    }

    .for-you-comment-action {
        border: 1px solid #f1d7c3;
        background: #fff8f2;
        color: #c9783e;
        border-radius: 999px;
        padding: 0.2rem 0.65rem;
        font-size: 0.74rem;
        font-weight: 700;
        cursor: pointer;
    }

    .for-you-comment-action.is-danger {
        color: #d25443;
    }

    .for-you-comment-edit-panel {
        margin-top: 0.55rem;
        padding: 0.55rem;
        border: 1px solid #f1d7c3;
        border-radius: 10px;
        background: #fffaf6;
    }

    .for-you-comment-edit-stars {
        display: flex;
        gap: 0.25rem;
        margin-bottom: 0.45rem;
    }

    .for-you-comment-edit-star {
        width: 1.9rem;
        height: 1.9rem;
        border: 1px solid #f1c29c;
        background: #fff5ec;
        color: #c9783e;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .for-you-comment-edit-star.active {
        background: #F28241;
        color: #fff;
        border-color: #F28241;
    }

    .for-you-comment-edit-actions {
        display: flex;
        gap: 0.4rem;
        margin-top: 0.5rem;
    }

    .for-you-comment-author {
        color: #8f4f23;
        font-weight: 700;
        text-decoration: none;
    }

    .for-you-comment-author:hover {
        color: #F28241;
        text-decoration: none;
    }

    .for-you-comment-form-area {
        flex-shrink: 0;
        padding: 0.5rem 0.6rem;
        border-top: 1px solid #f1c29c;
    }

    .for-you-comment-label {
        color: #8f5b37;
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 0.35rem;
    }

    .for-you-comment-reaction {
        margin-top: 0.45rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border: 1px solid #f1d7c3;
        background: #fff8f2;
        color: #c9783e;
        border-radius: 999px;
        padding: 0.22rem 0.65rem;
        font-size: 0.76rem;
        font-weight: 700;
        cursor: pointer;
    }

    .for-you-comment-reaction.active {
        background: #F28241;
        color: #fff;
        border-color: #F28241;
    }

    @media (max-width: 768px) {
        .for-you-wrap {
            min-height: calc(100vh - 68.19px);
        }
        .for-you-video-box {
            width: 92vw;
            height: 72vh;
        }
        .for-you-comments-panel {
            display: none !important;
        }
        .for-you-side {
            padding-left: 10px;
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
            Videos para ti
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
                    <div class="text-muted small text-center py-3">Sin comentarios todavÃ­a.</div>
                </div>
                <div class="for-you-comment-form-area" id="forYouCommentForm">
                    <div class="for-you-comment-label">Agregar comentario</div>
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

    if (!recipes.length) {
        return;
    }

    let currentIndex = 0;
    let currentRating = 5;
    let commentsOpen = false;

    const csrf = $('meta[name="csrf-token"]').attr('content');
    const currentUserId = @json(auth()->id());

    const videoBox = document.getElementById('forYouVideoBox');
    const sideBox = document.getElementById('forYouSide');
    const commentsPanel = document.getElementById('forYouCommentsPanel');
    const commentsList = document.getElementById('forYouCommentsList');
    const commentInput = document.getElementById('forYouCommentInput');
    const commentError = document.getElementById('forYouCommentError');
    const player = document.getElementById('forYouPlayer');
    let wheelLock = false;

    const escHtml = (value) => String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

    const stars = (value) => {
        const safeValue = Math.max(0, Math.min(5, Number(value) || 0));
        return '★'.repeat(safeValue) + '☆'.repeat(5 - safeValue);
    };

    const renderCommentItem = (comment) => {
        const authorHtml = comment.user_id
            ? `<a href="/perfil/${comment.user_id}" class="for-you-comment-author">${escHtml(comment.user)}</a>`
            : `<strong>${escHtml(comment.user)}</strong>`;
        const isOwnComment = Number(comment.user_id) === Number(currentUserId);
        const canReact = Number(comment.user_id) !== Number(currentUserId);
        const editPanel = isOwnComment ? `
            <div class="for-you-comment-edit-panel" data-comment-id="${comment.id}" style="display:none;">
                <div class="for-you-comment-edit-stars" data-comment-id="${comment.id}" data-rating="${Number(comment.rating || 0)}">
                    ${[1, 2, 3, 4, 5].map((n) => `
                        <button type="button" class="for-you-comment-edit-star ${n <= Number(comment.rating || 0) ? 'active' : ''}" data-comment-id="${comment.id}" data-n="${n}">★</button>
                    `).join('')}
                </div>
                <textarea class="form-control form-control-sm for-you-comment-edit-textarea" rows="2">${escHtml(comment.comment || '')}</textarea>
                <div class="for-you-comment-edit-actions">
                    <button type="button" class="for-you-comment-action for-you-comment-save" data-comment-id="${comment.id}">Guardar</button>
                    <button type="button" class="for-you-comment-action for-you-comment-cancel" data-comment-id="${comment.id}">Cancelar</button>
                </div>
                <div class="for-you-comment-edit-error text-danger small mt-1" style="display:none;"></div>
            </div>
        ` : '';

        return `
            <div class="for-you-comment-item">
                <div class="d-flex justify-content-between" style="font-size:0.78rem;">
                    ${authorHtml}
                    <span class="text-muted">${escHtml(comment.created_at)}</span>
                </div>
                <div style="color:#F28241;font-size:0.78rem;">${stars(comment.rating)}</div>
                <div style="font-size:0.82rem;">${escHtml(comment.comment)}</div>
                ${isOwnComment ? `
                    <div class="for-you-comment-actions">
                        <button type="button" class="for-you-comment-action for-you-comment-edit" data-comment-id="${comment.id}">Editar</button>
                        <button type="button" class="for-you-comment-action is-danger for-you-comment-delete" data-comment-id="${comment.id}">Eliminar</button>
                    </div>
                    ${editPanel}
                ` : ''}
                ${canReact ? `
                    <button type="button" class="for-you-comment-reaction ${comment.reacted_by_current_user ? 'active' : ''}" data-comment-id="${comment.id}">
                        <i class="fas fa-heart"></i>
                        <span>${Number(comment.reactions_count || 0)}</span>
                    </button>
                ` : ''}
            </div>
        `;
    };

    function stopCurrentMedia() {
        const video = videoBox.querySelector('.for-you-video');
        if (video) {
            video.pause();
            video.src = '';
        }

        const embed = videoBox.querySelector('.for-you-embed');
        if (embed) {
            embed.src = '';
        }
    }

    function renderComments(comments) {
        if (!comments.length) {
            commentsList.innerHTML = '<div class="for-you-comments-empty text-muted small text-center py-2">Sin comentarios todavía.</div>';
            return;
        }

        commentsList.innerHTML = comments.map(renderCommentItem).join('');
    }

    function loadComments(recipeId) {
        commentsList.innerHTML = '<div class="text-muted small text-center py-2">Cargando...</div>';

        $.ajax({
            url: "{{ route('recipes.show', ':id') }}".replace(':id', recipeId),
            method: 'GET',
            headers: { 'X-CSRF-TOKEN': csrf },
            success: function (response) {
                renderComments(response.comments || []);
            },
            error: function () {
                commentsList.innerHTML = '<div class="text-danger small text-center py-2">Error al cargar.</div>';
            }
        });
    }

    function loadRecipe(index) {
        const recipe = recipes[index];

        stopCurrentMedia();

        let mediaHtml = '';
        const embedBase = recipe.video_embed_url;
        const directUrl = recipe.video_direct_url || recipe.video;

        if (directUrl) {
            mediaHtml = `<video class="for-you-video" playsinline preload="metadata" id="fyVideo" loop>
                <source src="${escHtml(directUrl)}" type="video/mp4">
            </video>`;
        } else if (embedBase) {
            const separator = embedBase.includes('?') ? '&' : '?';
            const src = embedBase + separator + 'autoplay=1&mute=0&controls=1&playsinline=1';
            mediaHtml = `<iframe class="for-you-embed" id="fyEmbed"
                src="${escHtml(src)}"
                allow="autoplay; encrypted-media; picture-in-picture"
                allowfullscreen
                referrerpolicy="strict-origin-when-cross-origin"></iframe>`;
        }

        videoBox.innerHTML = mediaHtml;

        const video = videoBox.querySelector('.for-you-video');
        if (video) {
            video.defaultMuted = false;
            video.muted = false;
            video.volume = 1;
            video.play().catch(() => {
                video.muted = true;
                video.play().catch(() => {});
            });
        }

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
                </a>
            `;
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

        if (commentsOpen) {
            loadComments(recipe.id);
        }
    }

    function goTo(index) {
        currentIndex = ((index % recipes.length) + recipes.length) % recipes.length;
        loadRecipe(currentIndex);
    }

    if (player) {
        player.addEventListener('wheel', function (event) {
            if (wheelLock) {
                event.preventDefault();
                return;
            }

            if (Math.abs(event.deltaY) < 12) {
                return;
            }

            event.preventDefault();
            wheelLock = true;
            goTo(event.deltaY > 0 ? currentIndex + 1 : currentIndex - 1);

            window.setTimeout(function () {
                wheelLock = false;
            }, 420);
        }, { passive: false });
    }

    $(document).on('click', '#btnLike', function () {
        const btn = $(this);
        const recipeId = btn.data('recipe-id');

        $.ajax({
            url: "{{ url('favorites/toggle') }}/" + recipeId,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            success: function (response) {
                if (!response.success) {
                    return;
                }

                const icon = btn.find('.for-you-like');
                const count = btn.find('#likeCount');
                const currentCount = parseInt(count.text(), 10) || 0;

                if (response.is_favorite) {
                    icon.addClass('active');
                    count.text(currentCount + 1);
                    recipes[currentIndex].is_favorite = true;
                    recipes[currentIndex].favorited_by_count = currentCount + 1;
                } else {
                    icon.removeClass('active');
                    count.text(Math.max(0, currentCount - 1));
                    recipes[currentIndex].is_favorite = false;
                    recipes[currentIndex].favorited_by_count = Math.max(0, currentCount - 1);
                }
            },
            error: function (xhr) {
                if (xhr.status === 401) {
                    window.location.href = "{{ route('login') }}";
                }
            }
        });
    });

    $(document).on('click', '#btnComments', function () {
        commentsOpen = !commentsOpen;

        if (commentsOpen) {
            commentsPanel.classList.add('open');
            loadComments(recipes[currentIndex].id);
            return;
        }

        commentsPanel.classList.remove('open');
    });

    $('#btnSubmitComment').on('click', function () {
        const comment = (commentInput.value || '').trim();
        const recipeId = recipes[currentIndex].id;
        const url = "{{ route('recipes.comments', ':id') }}".replace(':id', recipeId);
        const payload = { rating: currentRating, comment };

        commentError.style.display = 'none';

        $.ajax({
            url,
            method: 'POST',
            data: payload,
            headers: { 'X-CSRF-TOKEN': csrf },
            success: function (response) {
                commentInput.value = '';

                const countEl = document.getElementById('commentsCount');
                if (countEl) {
                    countEl.textContent = (parseInt(countEl.textContent, 10) || 0) + 1;
                }

                recipes[currentIndex].comments_count++;

                const newItem = renderCommentItem(response.comment);
                if (commentsList.querySelector('.for-you-comments-empty')) {
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

                const message = xhr.responseJSON?.errors?.comment?.[0]
                    || xhr.responseJSON?.message
                    || 'Error al enviar el comentario.';

                commentError.textContent = message;
                commentError.style.display = 'block';
            }
        });
    });

    $(document).on('click', '.for-you-comment-reaction', function () {
        const btn = $(this);
        const commentId = btn.data('comment-id');

        $.ajax({
            url: "{{ route('recipes.comments.react', ':id') }}".replace(':id', commentId),
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            success: function (response) {
                btn.toggleClass('active', !!response.reacted);
                btn.find('span').text(parseInt(response.reactions_count, 10) || 0);
            },
            error: function (xhr) {
                if (xhr.status === 401) {
                    window.location.href = "{{ route('login') }}";
                }
            }
        });
    });

    $(document).on('click', '.for-you-comment-edit', function () {
        const commentId = $(this).data('comment-id');
        $(`.for-you-comment-edit-panel[data-comment-id="${commentId}"]`).show();
    });

    $(document).on('click', '.for-you-comment-cancel', function () {
        const commentId = $(this).data('comment-id');
        $(`.for-you-comment-edit-panel[data-comment-id="${commentId}"]`).hide();
    });

    $(document).on('click', '.for-you-comment-edit-star', function () {
        const commentId = $(this).data('comment-id');
        const rating = parseInt($(this).data('n'), 10) || 1;
        const row = $(`.for-you-comment-edit-stars[data-comment-id="${commentId}"]`);
        row.attr('data-rating', rating);
        row.find('.for-you-comment-edit-star').each(function () {
            $(this).toggleClass('active', (parseInt($(this).data('n'), 10) || 0) <= rating);
        });
    });

    $(document).on('click', '.for-you-comment-save', function () {
        const btn = $(this);
        const commentId = btn.data('comment-id');
        const panel = $(`.for-you-comment-edit-panel[data-comment-id="${commentId}"]`);
        const textarea = panel.find('.for-you-comment-edit-textarea');
        const errorEl = panel.find('.for-you-comment-edit-error');
        const rating = parseInt(panel.find('.for-you-comment-edit-stars').attr('data-rating'), 10) || 5;

        errorEl.hide();
        btn.prop('disabled', true);

        $.ajax({
            url: "{{ route('recipes.comments.update', ':id') }}".replace(':id', commentId),
            method: 'PATCH',
            data: { rating: rating, comment: textarea.val().trim() },
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            success: function () {
                btn.prop('disabled', false);
                loadComments(recipes[currentIndex].id);
            },
            error: function (xhr) {
                btn.prop('disabled', false);
                const message = xhr.responseJSON?.errors?.comment?.[0]
                    || xhr.responseJSON?.message
                    || 'Error al actualizar el comentario.';
                errorEl.text(message).show();
            }
        });
    });

    $(document).on('click', '.for-you-comment-delete', function () {
        const btn = $(this);
        const commentId = btn.data('comment-id');

        if (!window.confirm('¿Eliminar comentario?')) {
            return;
        }

        $.ajax({
            url: "{{ route('recipes.comments.delete', ':id') }}".replace(':id', commentId),
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            success: function () {
                recipes[currentIndex].comments_count = Math.max(0, (recipes[currentIndex].comments_count || 0) - 1);
                const countEl = document.getElementById('commentsCount');
                if (countEl) {
                    countEl.textContent = Math.max(0, (parseInt(countEl.textContent, 10) || 0) - 1);
                }
                loadComments(recipes[currentIndex].id);
            },
            error: function (xhr) {
                if (xhr.status === 401) {
                    window.location.href = "{{ route('login') }}";
                }
            }
        });
    });

    $(document).on('click', '.btn-follow-author', function (event) {
        event.preventDefault();
        event.stopPropagation();

        const btn = $(this);
        const userId = btn.data('user-id');

        $.ajax({
            url: "{{ route('profile.follow', ':id') }}".replace(':id', userId),
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf },
            success: function () {
                btn.remove();
            },
            error: function (xhr) {
                if (xhr.status === 401) {
                    window.location.href = "{{ route('login') }}";
                }
            }
        });
    });

    loadRecipe(0);
});
</script>
@stop
