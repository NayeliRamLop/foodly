<script>
    window.createRecipePreviewModalController = function (config) {
        const modalElement = document.getElementById(config.modalId);
        const modalBody = document.getElementById(config.bodyId);
        const modalTitle = document.getElementById(config.titleId);

        if (!modalElement || !modalBody || !modalTitle) {
            return null;
        }

        const bootstrapModal = window.bootstrap ? new window.bootstrap.Modal(modalElement) : null;
        const isGuest = Boolean(config.isGuest);
        modalElement.classList.toggle('is-guest-preview', isGuest);

        const showModal = () => {
            if (bootstrapModal) {
                bootstrapModal.show();
                return;
            }

            if (window.jQuery && typeof window.jQuery(modalElement).modal === 'function') {
                window.jQuery(modalElement).modal('show');
            }
        };

        const escapeHtml = (value) => String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

        const renderListItems = (value, tagName) => String(value || '')
            .split(/\r?\n/)
            .map((item) => item.trim())
            .filter(Boolean)
            .map((item) => `<${tagName}>${escapeHtml(item)}</${tagName}>`)
            .join('');

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
                                <source src="/storage/${escapeHtml(response.video)}" type="video/mp4">
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

        const renderComments = (comments) => {
            if (!Array.isArray(comments) || !comments.length) {
                return '';
            }

            return `
                <div class="recipe-section">
                    <h5 class="recipe-section-title"><i class="fas fa-comments mr-1"></i> Comentarios</h5>
                    ${comments.map((comment) => `
                        <div class="comment-item">
                            <div class="d-flex justify-content-between flex-wrap">
                                <strong>${escapeHtml(comment.user)}</strong>
                                <span class="text-muted small">${escapeHtml(comment.created_at)}</span>
                            </div>
                            <div class="mb-1">${'&#9733;'.repeat(Number(comment.rating || 0))}${'&#9734;'.repeat(5 - Number(comment.rating || 0))}</div>
                            <div>${escapeHtml(comment.comment)}</div>
                        </div>
                    `).join('')}
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
                    ${response.image
                        ? `<img src="/storage/${escapeHtml(response.image)}" alt="${escapeHtml(response.recipe_title)}">`
                        : `<div class="text-center py-4">
                                <i class="fas fa-image fa-5x" style="color: #F28241;"></i>
                                <p class="mt-2 mb-0">Sin imagen</p>
                           </div>`
                    }
                </div>
                <h3 class="recipe-modal-title">${escapeHtml(response.recipe_title)}</h3>
                <p class="recipe-modal-description">${escapeHtml(response.recipe_description)}</p>
                <div class="recipe-modal-meta">
                    <div class="recipe-modal-author">
                        <i class="fas fa-user-circle mr-1"></i> Creado por: ${authorHtml}
                    </div>
                    ${renderMetrics(response)}
                </div>
                <div class="recipe-modal-tags">
                    ${renderTags(response)}
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
                    `
                }
                ${renderGuestInvite()}
            `;
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
        };

        const open = async (recipeId) => {
            const url = config.showUrlTemplate.replace('__ID__', recipeId);
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
                modalBody.innerHTML = `
                    <div class="alert alert-danger mb-0">
                        No se pudo cargar la receta.
                    </div>
                `;
            }
        };

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

        return { open };
    };
</script>
