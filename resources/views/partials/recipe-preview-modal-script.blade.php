
<script>
    window.createRecipePreviewModalController = function (config) {
        const modalElement = document.getElementById(config.modalId);
        const modalBody = document.getElementById(config.bodyId);
        const modalTitle = document.getElementById(config.titleId);
        const footerActionsContainer = modalElement
            ? modalElement.querySelector('.recipe-preview-footer-actions')
            : null;

        if (!modalElement || !modalBody || !modalTitle) {
            return null;
        }

        const bootstrapModal = window.bootstrap ? new window.bootstrap.Modal(modalElement) : null;
        const isGuest = Boolean(config.isGuest);
        modalElement.classList.toggle('is-guest-preview', isGuest);
        let currentRecipeIndex = -1;
        let currentRating = 5;

        const resolveRecipeIds = () => {
            const source = typeof config.getRecipeIds === 'function'
                ? config.getRecipeIds()
                : config.recipeIds;

            if (!Array.isArray(source)) {
                return [];
            }

            return source
                .map((value) => Number(value))
                .filter((value, index, items) => Number.isFinite(value) && items.indexOf(value) === index);
        };

        const syncCurrentRecipeIndex = (recipeId) => {
            const recipeIds = resolveRecipeIds();
            currentRecipeIndex = recipeIds.indexOf(Number(recipeId));
            return recipeIds;
        };

        const showModal = () => {
            if (bootstrapModal) {
                bootstrapModal.show();
                return;
            }

            if (window.jQuery && typeof window.jQuery(modalElement).modal === 'function') {
                window.jQuery(modalElement).modal('show');
            }
        };

        const hideModal = () => {
            if (bootstrapModal) {
                bootstrapModal.hide();
                return;
            }

            if (window.jQuery && typeof window.jQuery(modalElement).modal === 'function') {
                window.jQuery(modalElement).modal('hide');
            }
        };

        const escapeHtml = (value) => String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

        const resolveMediaUrl = (value) => {
            const source = String(value ?? '').trim();

            if (!source) {
                return '';
            }

            if (/^(https?:)?\/\//i.test(source) || source.startsWith('data:') || source.startsWith('blob:')) {
                return source;
            }

            return `/${source.replace(/^\/+/, '')}`;
        };

        const renderListItems = (value, tagName) => String(value || '')
            .split(/\r?\n/)
            .map((item) => item.trim())
            .filter(Boolean)
            .map((item) => `<${tagName}>${escapeHtml(item)}</${tagName}>`)
            .join('');

        const renderStars = (rating) => {
            const value = Math.max(0, Math.min(5, Number(rating) || 0));
            return '&#9733;'.repeat(value) + '&#9734;'.repeat(5 - value);
        };

        const renderEditorStars = (rating, commentId, scope) => [1, 2, 3, 4, 5].map((n) => `
            <button type="button" class="comment-edit-star${n <= Number(rating || 0) ? ' active' : ''}" data-comment-id="${commentId}" data-scope="${scope}" data-n="${n}">★</button>
        `).join('');

        const renderPreviewListItems = (value, tagName, limit) => String(value || '')
            .split(/\r?\n/)
            .map((item) => item.trim())
            .filter(Boolean)
            .slice(0, limit)
            .map((item) => `<${tagName}>${escapeHtml(item)}</${tagName}>`)
            .join('');

        const badge = (icon, value, accent = false) => `
            <span class="recipe-modal-badge${accent ? ' is-accent' : ''}">
                <i class="fas ${icon}"></i> ${escapeHtml(value)}
            </span>
        `;

        const renderNavigationButtons = () => {
            const recipeIds = resolveRecipeIds();
            const showPrevNav = config.showPrevNav !== false;
            const showNextNav = config.showNextNav !== false;

            if (recipeIds.length <= 1 || currentRecipeIndex === -1 || (!showPrevNav && !showNextNav)) {
                return '';
            }

            const hasPrev = currentRecipeIndex > 0;
            const hasNext = currentRecipeIndex < recipeIds.length - 1;

            return `
                ${showPrevNav ? `
                    <button type="button" class="modal-nav-btn left" data-dir="prev" ${hasPrev ? '' : 'disabled'} aria-label="Receta anterior">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                ` : ''}
                ${showNextNav ? `
                    <button type="button" class="modal-nav-btn right" data-dir="next" ${hasNext ? '' : 'disabled'} aria-label="Receta siguiente">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                ` : ''}
            `;
        };

        const renderTags = (response) => {
            const tags = [];

            if (response.category && response.category.name) {
                tags.push(badge('fa-tag', response.category.name, true));
            }

            if (response.subcategory && response.subcategory.name) {
                tags.push(badge('fa-tags', response.subcategory.name));
            }

            [
                ['brand', 'fa-copyright'],
                ['dish_type', 'fa-utensils'],
                ['daily_category', 'fa-sun'],
                ['special_occasion', 'fa-gift'],
                ['baking_category', 'fa-birthday-cake'],
                ['seasonality', 'fa-leaf'],
                ['preparation_method', 'fa-fire'],
            ].forEach(([field, icon]) => {
                if (response[field]) {
                    tags.push(badge(icon, response[field]));
                }
            });

            return tags.join('');
        };

        const renderMetrics = (response) => `
            <div class="recipe-modal-badges">
                ${badge('fa-clock', `${response.preparation_time} min`)}
                ${badge('fa-utensil-spoon', response.difficulty)}
                ${badge('fa-star', Number(response.avg_rating || 0).toFixed(1))}
            </div>
        `;

        const renderVideo = (response) => {
            if (response.video) {
                return `
                    <div class="recipe-section">
                        <h5 class="recipe-section-title"><i class="fas fa-video mr-1"></i> Video</h5>
                        <div class="recipe-video-wrap">
                            <video controls class="w-100 rounded" style="max-height: 420px;">
                                <source src="${escapeHtml(resolveMediaUrl(response.video))}" type="video/mp4">
                            </video>
                        </div>
                    </div>
                `;
            }

            if (response.video_link_type === 'direct' && response.video_direct_url) {
                return `
                    <div class="recipe-section">
                        <h5 class="recipe-section-title"><i class="fas fa-video mr-1"></i> Video</h5>
                        <div class="recipe-video-wrap">
                            <video controls class="w-100 rounded" style="max-height: 420px;">
                                <source src="${escapeHtml(response.video_direct_url)}" type="video/mp4">
                            </video>
                        </div>
                    </div>
                `;
            }

            if (response.video_embed_url) {
                return `
                    <div class="recipe-section">
                        <h5 class="recipe-section-title"><i class="fas fa-video mr-1"></i> Video</h5>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item rounded"
                                src="${escapeHtml(response.video_embed_url)}"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                                referrerpolicy="strict-origin-when-cross-origin"></iframe>
                        </div>
                    </div>
                `;
            }

            return '';
        };

        const currentUserId = @json(auth()->id());

        const renderComments = (comments) => {
            if (!Array.isArray(comments) || !comments.length) {
                return '';
            }

            return `
                <div class="recipe-section">
                    <h5 class="recipe-section-title"><i class="fas fa-comments mr-1"></i> Comentarios</h5>
                    ${comments.map((comment) => {
                        const authorUrl = isGuest
                            ? (config.profilePromptUrl || config.registerUrl)
                            : `${config.profileBaseUrl}/${comment.user_id}`;
                        const authorHtml = comment.user_id
                            ? `<a href="${authorUrl}" class="text-decoration-none font-weight-bold recipe-author-link">${escapeHtml(comment.user)}</a>`
                            : `<strong>${escapeHtml(comment.user)}</strong>`;
                        const isOwnComment = !isGuest && currentUserId && Number(comment.user_id) === Number(currentUserId);
                        const ownerActions = isOwnComment
                            ? `<div class="comment-owner-actions">
                                    <button type="button" class="comment-inline-action comment-edit-toggle" data-comment-id="${comment.id}" title="Editar">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button type="button" class="comment-inline-action is-danger comment-delete-btn" data-comment-id="${comment.id}" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                               </div>`
                            : '';
                        const reactionBtn = !isGuest && !isOwnComment
                            ? `<button type="button" class="comment-reaction-btn${comment.reacted_by_current_user ? ' active' : ''}" data-comment-id="${comment.id}" title="${comment.reacted_by_current_user ? 'Quitar reacción' : 'Reaccionar'}">
                                    <i class="fas fa-heart"></i>
                                    <span>${Number(comment.reactions_count || 0)}</span>
                               </button>`
                            : '';
                        return `
                            <div class="comment-item">
                                <div class="d-flex justify-content-between flex-wrap align-items-center">
                                    <div>${authorHtml}</div>
                                    ${ownerActions}
                                    <span class="text-muted small">${escapeHtml(comment.created_at)}</span>
                                </div>
                                <div class="mb-1">${renderStars(comment.rating)}</div>
                                <div class="comment-content-text">${comment.comment ? escapeHtml(comment.comment) : '<span class="comment-empty-note">Calificó esta receta.</span>'}</div>
                                ${isOwnComment ? `
                                    <div class="comment-edit-panel" data-comment-id="${comment.id}" style="display:none;">
                                        <div class="comment-edit-stars">${renderEditorStars(comment.rating, comment.id, 'modal')}</div>
                                        <textarea class="form-control comment-edit-textarea" rows="3">${escapeHtml(comment.comment || '')}</textarea>
                                        <div class="comment-edit-actions">
                                            <button type="button" class="comment-save-btn" data-comment-id="${comment.id}">Guardar</button>
                                            <button type="button" class="comment-cancel-btn" data-comment-id="${comment.id}">Cancelar</button>
                                        </div>
                                        <div class="comment-edit-error text-danger small mt-1" style="display:none;"></div>
                                    </div>
                                ` : ''}
                                ${reactionBtn ? `<div class="comment-reactions-row">${reactionBtn}</div>` : ''}
                            </div>
                        `;
                    }).join('')}
                </div>
            `;
        };

        const renderRatingForms = (recipeId) => {
            if (isGuest) return '';
            const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';
            const stars = [1,2,3,4,5].map(n =>
                `<button type="button" class="modal-star${n <= currentRating ? ' active' : ''}" data-n="${n}" aria-label="Calificar con ${n} estrella${n === 1 ? '' : 's'}">★</button>`
            ).join('');
            return `
                <div class="recipe-section modal-rating-section">
                    <div class="modal-star-row mb-2" role="group" aria-label="Calificación con estrellas">${stars}</div>
                    <div class="modal-comment-label">Agregar comentario</div>
                    <textarea class="form-control mb-2 modal-comment-textarea" rows="3"
                        placeholder="Escribe tu comentario..."
                        style="border-radius:10px;border:1px solid #f0d5be;font-size:0.93rem;"></textarea>
                    <button type="button" class="btn btn-sm view-recipe-btn modal-comment-submit-btn"
                        data-recipe-id="${recipeId}" data-csrf="${escapeHtml(csrf)}">
                        <i class="fas fa-paper-plane mr-1"></i> Enviar
                    </button>
                    <div class="modal-comment-error-msg text-danger small mt-1" style="display:none;"></div>
                </div>
            `;
        };

        const renderGuestInvite = () => {
            if (!isGuest) {
                return '';
            }

            return `
                <div class="recipe-login-invite">
                    <div class="recipe-login-brand">
                        <img src="${config.logoUrl}" alt="Foodly">
                    </div>
                    <div class="invite-pill">
                        <i class="fas fa-star"></i> Sigue descubriendo en Foodly
                    </div>
                    <h5>Desbloquea la receta completa</h5>
                    <p>Ya viste un adelanto. Inicia sesion o crea tu cuenta para ver todos los ingredientes, el paso a paso, el video y cada detalle de esta receta.</p>
                    <div class="recipe-login-actions">
                        <a href="${config.loginUrl}" class="btn view-recipe-btn invite-action">
                            <i class="fas fa-sign-in-alt mr-1"></i> Iniciar sesion
                        </a>
                        <a href="${config.registerUrl}" class="invite-secondary">
                            Crear una cuenta
                        </a>
                    </div>
                    <div class="recipe-login-caption">Accede al contenido completo y sigue explorando recetas.</div>
                </div>
            `;
        };

        const renderGuestLockedPreview = (response) => {
            const sections = [];

            if (response.ingredients) {
                sections.push(`
                    <div class="recipe-section">
                        <h5 class="recipe-section-title"><i class="fas fa-list-ul mr-1"></i> Ingredientes</h5>
                        <ul class="pl-3">${renderPreviewListItems(response.ingredients, 'li', 4)}</ul>
                    </div>
                `);
            }

            if (response.instructions) {
                sections.push(`
                    <div class="recipe-section">
                        <h5 class="recipe-section-title"><i class="fas fa-list-ol mr-1"></i> Preparacion</h5>
                        <ol class="pl-3">${renderPreviewListItems(response.instructions, 'li', 3)}</ol>
                    </div>
                `);
            }

            if (response.comments && response.comments.length) {
                sections.push(`
                    <div class="recipe-section">
                        <h5 class="recipe-section-title"><i class="fas fa-comments mr-1"></i> Comentarios</h5>
                        <div class="comment-item" style="border-top: 0; margin-top: 0; padding-top: 0;">
                            <div class="d-flex justify-content-between flex-wrap">
                                <strong>${escapeHtml(response.comments[0].user)}</strong>
                                <span class="text-muted small">${escapeHtml(response.comments[0].created_at)}</span>
                            </div>
                            <div class="mb-1">${'&#9733;'.repeat(Number(response.comments[0].rating || 0))}${'&#9734;'.repeat(5 - Number(response.comments[0].rating || 0))}</div>
                            <div>${escapeHtml(response.comments[0].comment)}</div>
                        </div>
                    </div>
                `);
            }

            if (!sections.length) {
                return '';
            }

            return `
                <div class="recipe-modal-preview-wrap">
                    ${sections.join('')}
                </div>
                <div class="recipe-preview-note">Inicia sesion para ver la receta completa.</div>
            `;
        };

        const renderRecipe = (response) => {
            currentRating = 5;
            const authorName = response.user
                ? `${escapeHtml(response.user.name)} ${escapeHtml(response.user.last_name || '')}`.trim()
                : 'Administrador';
            const authorUrl = isGuest
                ? (config.profilePromptUrl || config.registerUrl)
                : `${config.profileBaseUrl}/${response.user.id}`;
            const authorHtml = response.user
                ? `<a href="${authorUrl}" class="text-decoration-none recipe-author-link"${isGuest ? ' title="Crea una cuenta para ver el perfil del autor"' : ''}>${authorName}</a>`
                : authorName;

            modalTitle.innerHTML = `<i class="fas fa-utensils mr-2"></i> ${escapeHtml(response.recipe_title)}`;
            modalBody.innerHTML = `
                <div class="recipe-modal-media">
                    ${renderNavigationButtons()}
                    <div class="recipe-modal-media-content">
                        ${response.image
                            ? `<img src="${escapeHtml(resolveMediaUrl(response.image))}" alt="${escapeHtml(response.recipe_title)}">`
                            : `<div class="text-center py-4">
                                    <i class="fas fa-image fa-5x" style="color: #F28241;"></i>
                                    <p class="mt-2 mb-0">Sin imagen</p>
                               </div>`
                        }
                    </div>
                </div>
                <h3 class="recipe-modal-title">${escapeHtml(response.recipe_title)}</h3>
                <p class="recipe-modal-description">${escapeHtml(response.recipe_description)}</p>
                <div class="recipe-modal-meta">
                    <div class="recipe-modal-author">
                        <i class="fas fa-user-circle mr-1"></i> Creado por: ${authorHtml}
                    </div>
                    <div class="recipe-modal-meta-stats">
                        ${renderMetrics(response)}
                    </div>
                    <div class="recipe-modal-tags">
                        ${renderTags(response)}
                    </div>
                </div>
                ${isGuest
                    ? renderGuestLockedPreview(response)
                    : `
                        ${response.ingredients ? `
                            <div class="recipe-section">
                                <h5 class="recipe-section-title"><i class="fas fa-list-ul mr-1"></i> Ingredientes</h5>
                                <ul class="pl-3">${renderListItems(response.ingredients, 'li')}</ul>
                            </div>
                        ` : ''}
                        ${response.instructions ? `
                            <div class="recipe-section">
                                <h5 class="recipe-section-title"><i class="fas fa-list-ol mr-1"></i> Preparacion</h5>
                                <ol class="pl-3">${renderListItems(response.instructions, 'li')}</ol>
                            </div>
                        ` : ''}
                        ${renderVideo(response)}
                        ${renderComments(response.comments)}
                        ${renderRatingForms(response.id)}
                    `
                }
                ${renderGuestInvite()}
            `;

            if (footerActionsContainer) {
                footerActionsContainer.innerHTML = typeof config.renderFooterActions === 'function'
                    ? (config.renderFooterActions(response) || '')
                    : '';

                if (typeof config.bindFooterActions === 'function') {
                    config.bindFooterActions(footerActionsContainer, response, {
                        modalElement,
                        showModal,
                        hideModal,
                    });
                }
            }
        };

        const navigate = (direction) => {
            const recipeIds = resolveRecipeIds();

            if (currentRecipeIndex === -1 || recipeIds.length <= 1) {
                return;
            }

            const nextIndex = direction === 'prev'
                ? currentRecipeIndex - 1
                : currentRecipeIndex + 1;

            if (nextIndex < 0 || nextIndex >= recipeIds.length) {
                return;
            }

            open(recipeIds[nextIndex]);
        };

        const renderLoading = () => {
            modalTitle.innerHTML = '<i class="fas fa-utensils mr-2"></i> Receta Completa';
            modalBody.innerHTML = `
                <div class="recipe-modal-loading">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                </div>
            `;

            if (footerActionsContainer) {
                footerActionsContainer.innerHTML = '';
            }
        };

        const open = async (recipeId) => {
            const url = config.showUrlTemplate.replace('__ID__', recipeId);
            syncCurrentRecipeIndex(recipeId);
            renderLoading();
            showModal();

            try {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('No se pudo cargar la receta');
                }

                renderRecipe(await response.json());
            } catch (error) {
                if (footerActionsContainer) {
                    footerActionsContainer.innerHTML = '';
                }

                modalBody.innerHTML = `
                    <div class="alert alert-danger mb-0">
                        No se pudo cargar la receta.
                    </div>
                `;
            }
        };

        modalElement.addEventListener('click', (event) => {
            // Star rating click
            const star = event.target.closest('.modal-star');
            if (star && modalBody.contains(star)) {
                const n = parseInt(star.getAttribute('data-n'), 10) || 1;
                currentRating = n;
                const row = star.closest('.modal-star-row');
                if (row) {
                    row.querySelectorAll('.modal-star').forEach(s => {
                        s.classList.toggle('active', parseInt(s.getAttribute('data-n'), 10) <= n);
                    });
                }
                return;
            }

            // Comment submit button
            const commentBtn = event.target.closest('.modal-comment-submit-btn');
            if (commentBtn && modalBody.contains(commentBtn)) {
                const recipeId = commentBtn.getAttribute('data-recipe-id');
                const csrf = commentBtn.getAttribute('data-csrf');
                const section = commentBtn.closest('.modal-rating-section');
                const textarea = section ? section.querySelector('.modal-comment-textarea') : null;
                const errorEl = section ? section.querySelector('.modal-comment-error-msg') : null;
                const comment = textarea ? textarea.value.trim() : '';
                if (errorEl) errorEl.style.display = 'none';
                commentBtn.disabled = true;
                fetch(`{{ url('/recipes') }}/${recipeId}/comments`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ rating: currentRating, comment })
                }).then(r => {
                    if (!r.ok) return r.json().then(d => { throw d; });
                    return r.json();
                }).then(() => {
                    commentBtn.disabled = false;
                    if (textarea) {
                        textarea.value = '';
                    }
                    open(parseInt(recipeId, 10));
                }).catch(err => {
                    commentBtn.disabled = false;
                    const msg = err?.errors?.comment?.[0] || err?.message || 'Error al enviar el comentario.';
                    if (errorEl) { errorEl.textContent = msg; errorEl.style.display = 'block'; }
                });
                return;
            }

            const reactionBtn = event.target.closest('.comment-reaction-btn');
            if (reactionBtn && modalBody.contains(reactionBtn)) {
                const commentId = reactionBtn.getAttribute('data-comment-id');
                const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';
                reactionBtn.disabled = true;
                fetch(`{{ url('/recipes/comments') }}/${commentId}/react`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' }
                }).then(r => {
                    if (!r.ok) return r.json().then(d => { throw d; });
                    return r.json();
                }).then((resp) => {
                    reactionBtn.disabled = false;
                    reactionBtn.classList.toggle('active', Boolean(resp.reacted));
                    const countEl = reactionBtn.querySelector('span');
                    if (countEl) {
                        countEl.textContent = Number(resp.reactions_count || 0);
                    }
                }).catch(() => {
                    reactionBtn.disabled = false;
                });
                return;
            }

            const editToggleBtn = event.target.closest('.comment-edit-toggle');
            if (editToggleBtn && modalBody.contains(editToggleBtn)) {
                const commentId = editToggleBtn.getAttribute('data-comment-id');
                const panel = modalBody.querySelector(`.comment-edit-panel[data-comment-id="${commentId}"]`);
                if (panel) {
                    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
                }
                return;
            }

            const cancelBtn = event.target.closest('.comment-cancel-btn');
            if (cancelBtn && modalBody.contains(cancelBtn)) {
                const commentId = cancelBtn.getAttribute('data-comment-id');
                const panel = modalBody.querySelector(`.comment-edit-panel[data-comment-id="${commentId}"]`);
                if (panel) {
                    panel.style.display = 'none';
                }
                return;
            }

            const editStar = event.target.closest('.comment-edit-star');
            if (editStar && modalBody.contains(editStar)) {
                const commentId = editStar.getAttribute('data-comment-id');
                const n = parseInt(editStar.getAttribute('data-n'), 10) || 1;
                const row = editStar.closest('.comment-edit-stars');
                if (row) {
                    row.setAttribute('data-rating', n);
                    row.querySelectorAll('.comment-edit-star').forEach((starEl) => {
                        starEl.classList.toggle('active', (parseInt(starEl.getAttribute('data-n'), 10) || 0) <= n);
                    });
                }
                return;
            }

            const saveBtn = event.target.closest('.comment-save-btn');
            if (saveBtn && modalBody.contains(saveBtn)) {
                const commentId = saveBtn.getAttribute('data-comment-id');
                const panel = modalBody.querySelector(`.comment-edit-panel[data-comment-id="${commentId}"]`);
                const textarea = panel ? panel.querySelector('.comment-edit-textarea') : null;
                const starsRow = panel ? panel.querySelector('.comment-edit-stars') : null;
                const errorEl = panel ? panel.querySelector('.comment-edit-error') : null;
                const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';
                const rating = parseInt(starsRow?.getAttribute('data-rating') || '5', 10) || 5;
                const comment = textarea ? textarea.value.trim() : '';
                if (errorEl) {
                    errorEl.style.display = 'none';
                }
                saveBtn.disabled = true;
                fetch(`{{ url('/recipes/comments') }}/${commentId}`, {
                    method: 'PATCH',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ rating, comment })
                }).then((r) => {
                    if (!r.ok) return r.json().then((d) => { throw d; });
                    return r.json();
                }).then(() => {
                    saveBtn.disabled = false;
                    if (currentRecipeIndex !== -1) {
                        const recipeIds = resolveRecipeIds();
                        open(recipeIds[currentRecipeIndex]);
                    }
                }).catch((err) => {
                    saveBtn.disabled = false;
                    const msg = err?.errors?.comment?.[0] || err?.message || 'Error al actualizar el comentario.';
                    if (errorEl) {
                        errorEl.textContent = msg;
                        errorEl.style.display = 'block';
                    }
                });
                return;
            }

            const deleteBtn = event.target.closest('.comment-delete-btn');
            if (deleteBtn && modalBody.contains(deleteBtn)) {
                if (!window.confirm('¿Eliminar comentario?')) {
                    return;
                }
                const commentId = deleteBtn.getAttribute('data-comment-id');
                const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';
                deleteBtn.disabled = true;
                fetch(`{{ url('/recipes/comments') }}/${commentId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
                }).then((r) => {
                    if (!r.ok) throw new Error('Error al eliminar el comentario.');
                    return r.json();
                }).then(() => {
                    deleteBtn.disabled = false;
                    if (currentRecipeIndex !== -1) {
                        const recipeIds = resolveRecipeIds();
                        open(recipeIds[currentRecipeIndex]);
                    }
                }).catch(() => {
                    deleteBtn.disabled = false;
                });
                return;
            }

            // Nav button
            const navButton = event.target.closest('.modal-nav-btn');
            if (!navButton || !modalElement.contains(navButton)) {
                return;
            }
            event.preventDefault();
            navigate(navButton.getAttribute('data-dir'));
        });

        document.addEventListener('keydown', (event) => {
            if (!modalElement.classList.contains('show')) {
                return;
            }

            const activeElement = document.activeElement;
            if (activeElement && ['INPUT', 'TEXTAREA', 'SELECT'].includes(activeElement.tagName)) {
                return;
            }

            if (event.key === 'ArrowLeft') {
                navigate('prev');
            }

            if (event.key === 'ArrowRight') {
                navigate('next');
            }
        });

        modalElement.addEventListener('hidden.bs.modal', () => {
            modalElement.querySelectorAll('video').forEach((video) => {
                video.pause();
                video.currentTime = 0;
            });
            modalElement.querySelectorAll('iframe').forEach((iframe) => {
                iframe.src = '';
            });
            renderLoading();
        });

        return { open, hide: hideModal, modalElement };
    };
</script>
