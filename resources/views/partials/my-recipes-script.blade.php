<script>
    document.addEventListener('DOMContentLoaded', function () {
        const manageModal = document.getElementById('manageRecipeModal');
        const manageForm = document.getElementById('recipeManageForm');
        const manageTitle = document.getElementById('manageRecipeModalLabel');
        const manageSubmit = document.getElementById('recipeManageSubmit');
        const manageMethod = document.getElementById('recipeManageMethod');
        const manageMode = document.getElementById('recipeFormMode');
        const manageRecipeId = document.getElementById('recipeFormRecipeId');
        const categorySelect = document.getElementById('manage_category_id');
        const subcategorySelect = document.getElementById('manage_subcategory_id');
        const brandCustomWrap = document.getElementById('manageBrandCustomWrap');
        const brandCustomInput = document.getElementById('manage_brand_custom');
        const imageWrap = document.getElementById('manageCurrentImageWrap');
        const imagePreview = document.getElementById('manageCurrentImage');
        const videoWrap = document.getElementById('manageCurrentVideoWrap');
        const videoPreview = document.getElementById('manageCurrentVideo');
        const recipeCatalog = @json($recipePayloads);
        const hasValidationErrors = @json($errors->any());
        const oldFormMode = @json(old('_recipe_form_mode', ''));
        const oldRecipeId = @json(old('_recipe_id', ''));
        const storeUrl = "{{ route('recipes.store') }}";
        const updateUrlTemplate = "{{ route('recipes.update', '__ID__') }}";
        const deleteUrlTemplate = "{{ route('recipes.destroy', '__ID__') }}";
        const csrfToken = "{{ csrf_token() }}";
        const modalInstance = window.bootstrap && window.bootstrap.Modal
            ? new window.bootstrap.Modal(manageModal)
            : null;
        let recipePreview = null;

        const openManageModal = () => {
            if (modalInstance) {
                modalInstance.show();
                return;
            }

            if (window.jQuery && typeof window.jQuery(manageModal).modal === 'function') {
                window.jQuery(manageModal).modal('show');
            }
        };

        const escapeHtml = (value) => String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

        const setFileLabel = (input) => {
            const label = input.closest('.custom-file')?.querySelector('.custom-file-label');
            if (!label) {
                return;
            }

            const fileName = input.files && input.files[0] ? input.files[0].name : label.getAttribute('data-default-label');
            label.textContent = fileName || 'Seleccionar archivo...';
        };

        const resetFileLabels = () => {
            manageModal.querySelectorAll('.custom-file-label').forEach((label) => {
                label.textContent = label.getAttribute('data-default-label') || 'Seleccionar archivo...';
            });

            manageModal.querySelectorAll('.custom-file-input').forEach((input) => {
                input.value = '';
            });
        };

        const syncBrandChips = (value) => {
            const normalized = String(value ?? '');
            const hiddenInput = document.getElementById('manage_brand');
            const availableValues = Array.from(
                manageModal.querySelectorAll('.brand-chip-group[data-target="manage_brand"] .brand-chip')
            ).map((chip) => String(chip.dataset.value ?? ''));
            const isCustomBrand = normalized !== '' && !availableValues.includes(normalized);
            const activeValue = isCustomBrand ? '__other__' : normalized;

            hiddenInput.value = isCustomBrand ? normalized : activeValue;

            manageModal.querySelectorAll('.brand-chip-group[data-target="manage_brand"] .brand-chip').forEach((chip) => {
                chip.classList.toggle('active', String(chip.dataset.value ?? '') === activeValue);
            });

            if (brandCustomWrap && brandCustomInput) {
                brandCustomWrap.classList.toggle('is-visible', isCustomBrand || activeValue === '__other__');

                if (isCustomBrand) {
                    brandCustomInput.value = normalized;
                } else if (activeValue !== '__other__') {
                    brandCustomInput.value = '';
                }

                if (activeValue === '__other__' && !isCustomBrand) {
                    hiddenInput.value = brandCustomInput.value.trim();
                    window.setTimeout(() => brandCustomInput.focus(), 0);
                }
            }
        };

        const syncSubcategories = (categoryId, selectedValue = '') => {
            const normalizedCategory = String(categoryId ?? '');
            const normalizedSelected = String(selectedValue ?? '');

            Array.from(subcategorySelect.options).forEach((option, index) => {
                if (index === 0) {
                    option.hidden = false;
                    option.disabled = false;
                    return;
                }

                const isVisible = option.dataset.category === normalizedCategory;
                option.hidden = !isVisible;
                option.disabled = !isVisible;
            });

            if (normalizedSelected && Array.from(subcategorySelect.options).some((option) => !option.hidden && option.value === normalizedSelected)) {
                subcategorySelect.value = normalizedSelected;
                return;
            }

            subcategorySelect.value = '';
        };

        const updateMediaPreview = (recipe = {}) => {
            if (recipe.image_url) {
                imageWrap.classList.add('is-visible');
                imagePreview.src = recipe.image_url;
                imagePreview.alt = recipe.recipe_title || 'Imagen actual';
            } else {
                imageWrap.classList.remove('is-visible');
                imagePreview.removeAttribute('src');
                imagePreview.removeAttribute('alt');
            }

            if (recipe.video_url) {
                videoWrap.classList.add('is-visible');
                videoPreview.innerHTML = `
                    <video controls>
                        <source src="${escapeHtml(recipe.video_url)}" type="video/mp4">
                    </video>
                `;
                return;
            }

            if (recipe.video_direct_url) {
                videoWrap.classList.add('is-visible');
                videoPreview.innerHTML = `
                    <video controls>
                        <source src="${escapeHtml(recipe.video_direct_url)}" type="video/mp4">
                    </video>
                `;
                return;
            }

            if (recipe.video_embed_url) {
                videoWrap.classList.add('is-visible');
                videoPreview.innerHTML = `
                    <iframe
                        src="${escapeHtml(recipe.video_embed_url)}"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen
                        referrerpolicy="strict-origin-when-cross-origin"></iframe>
                `;
                return;
            }

            videoWrap.classList.remove('is-visible');
            videoPreview.innerHTML = '';
        };

        const assignFieldValues = (recipe) => {
            document.getElementById('manage_title').value = recipe.recipe_title || '';
            document.getElementById('manage_description').value = recipe.recipe_description || '';
            document.getElementById('manage_ingredients').value = recipe.ingredients || '';
            document.getElementById('manage_instructions').value = recipe.instructions || '';
            document.getElementById('manage_preparation_time').value = recipe.preparation_time || '';
            document.getElementById('manage_difficulty').value = recipe.difficulty || '';
            document.getElementById('manage_category_id').value = recipe.category_id || '';
            document.getElementById('manage_video_link').value = recipe.video_link || '';
            document.getElementById('manage_dish_type').value = recipe.dish_type || '';
            document.getElementById('manage_daily_category').value = recipe.daily_category || '';
            document.getElementById('manage_special_occasion').value = recipe.special_occasion || '';
            document.getElementById('manage_baking_category').value = recipe.baking_category || '';
            document.getElementById('manage_seasonality').value = recipe.seasonality || '';
            document.getElementById('manage_preparation_method').value = recipe.preparation_method || '';
            syncBrandChips(recipe.brand || '');
            syncSubcategories(recipe.category_id || '', recipe.subcategory_id || '');
        };

        const configureCreateMode = (preserveValues = false) => {
            if (!preserveValues) {
                manageForm.reset();
                resetFileLabels();
            }

            manageTitle.innerHTML = '<i class="fas fa-plus-circle mr-2"></i> Agregar receta';
            manageSubmit.innerHTML = '<i class="fas fa-save mr-1"></i> Guardar receta';
            manageForm.action = storeUrl;
            manageMethod.disabled = true;
            manageMethod.value = 'POST';
            manageMode.value = 'create';
            manageRecipeId.value = '';

            if (!preserveValues) {
                syncBrandChips('');
                syncSubcategories('', '');
                updateMediaPreview({});
            }
        };

        const configureEditMode = (recipeId, preserveValues = false) => {
            const recipe = recipeCatalog[String(recipeId)];
            if (!recipe) {
                return;
            }

            if (!preserveValues) {
                manageForm.reset();
                resetFileLabels();
            }

            manageTitle.innerHTML = '<i class="fas fa-edit mr-2"></i> Editar receta';
            manageSubmit.innerHTML = '<i class="fas fa-save mr-1"></i> Guardar cambios';
            manageForm.action = updateUrlTemplate.replace('__ID__', recipe.id);
            manageMethod.disabled = false;
            manageMethod.value = 'PUT';
            manageMode.value = 'edit';
            manageRecipeId.value = recipe.id;

            if (!preserveValues) {
                assignFieldValues(recipe);
            } else {
                syncBrandChips(document.getElementById('manage_brand').value);
                syncSubcategories(categorySelect.value, subcategorySelect.value);
            }

            updateMediaPreview(recipe);
        };

        const openEditRecipe = (recipeId) => {
            configureEditMode(recipeId);

            if (recipePreview && typeof recipePreview.hide === 'function') {
                recipePreview.hide();
            }

            window.setTimeout(openManageModal, 180);
        };

        document.querySelectorAll('.recipe-card').forEach((card) => {
            card.addEventListener('mouseenter', function () {
                const embed = card.querySelector('.recipe-embed-preview iframe');
                if (embed) {
                    const embedSrc = embed.getAttribute('data-src');
                    if (embedSrc && embed.getAttribute('src') !== embedSrc) {
                        embed.setAttribute('src', embedSrc);
                    }
                }

                const video = card.querySelector('.recipe-video-preview');
                if (!video) {
                    return;
                }

                const runLoop = () => {
                    video.currentTime = 0;
                    const playPromise = video.play();
                    if (playPromise && typeof playPromise.catch === 'function') {
                        playPromise.catch(() => {});
                    }

                    const loopTimeoutId = window.setTimeout(() => {
                        video.pause();
                        video.currentTime = 0;
                        if (card.matches(':hover')) {
                            runLoop();
                        }
                    }, 2000);

                    card.dataset.previewLoopTimeoutId = String(loopTimeoutId);
                };

                runLoop();
            });

            card.addEventListener('mouseleave', function () {
                const timeoutId = Number(card.dataset.previewLoopTimeoutId || 0);
                if (timeoutId) {
                    window.clearTimeout(timeoutId);
                    delete card.dataset.previewLoopTimeoutId;
                }

                const video = card.querySelector('.recipe-video-preview');
                if (video) {
                    video.pause();
                    video.currentTime = 0;
                }

                const embed = card.querySelector('.recipe-embed-preview iframe');
                if (embed) {
                    embed.setAttribute('src', '');
                }
            });
        });

        document.querySelectorAll('.add-recipe-trigger').forEach((button) => {
            button.addEventListener('click', function () {
                configureCreateMode();
                openManageModal();
            });
        });

        manageModal.querySelectorAll('.custom-file-input').forEach((input) => {
            input.addEventListener('change', function () {
                setFileLabel(this);
            });
        });

        manageModal.addEventListener('click', function (event) {
            const brandChip = event.target.closest('.brand-chip');
            if (!brandChip) {
                return;
            }

            syncBrandChips(brandChip.dataset.value || '');
        });

        if (brandCustomInput) {
            brandCustomInput.addEventListener('input', function () {
                document.getElementById('manage_brand').value = this.value.trim();
            });
        }

        categorySelect.addEventListener('change', function () {
            syncSubcategories(this.value, '');
        });

        const clearCurrentMedia = () => {
            videoPreview.innerHTML = '';
            imagePreview.removeAttribute('src');
            imageWrap.classList.remove('is-visible');
            videoWrap.classList.remove('is-visible');
        };

        if (window.jQuery && typeof window.jQuery.fn.modal === 'function') {
            window.jQuery(manageModal).on('hidden.bs.modal', clearCurrentMedia);
        } else {
            manageModal.addEventListener('hidden.bs.modal', clearCurrentMedia);
        }

        if (hasValidationErrors) {
            if (oldFormMode === 'edit' && oldRecipeId) {
                configureEditMode(oldRecipeId, true);
            } else {
                configureCreateMode(true);
                syncBrandChips(document.getElementById('manage_brand').value);
                syncSubcategories(categorySelect.value, subcategorySelect.value);
            }

            openManageModal();
        } else {
            configureCreateMode();
        }

        recipePreview = window.createRecipePreviewModalController({
            modalId: 'myRecipesPreviewModal',
            bodyId: 'myRecipesPreviewModalBody',
            titleId: 'myRecipesPreviewModalLabel',
            profileBaseUrl: "{{ url('/perfil') }}",
            showUrlTemplate: "{{ route('recipes.show', '__ID__') }}",
            isGuest: false,
            loginUrl: "{{ route('login') }}",
            registerUrl: "{{ route('user.create') }}",
            profilePromptUrl: "{{ route('user.create') }}?intent=profile#crear-cuenta",
            logoUrl: "{{ asset('images/logo.png') }}",
            renderFooterActions: (recipe) => `
                <button type="button" class="btn btn-sm my-recipe-secondary preview-edit-recipe" data-recipe-id="${recipe.id}">
                    <i class="fas fa-edit mr-1"></i> Editar receta
                </button>
                <form action="${deleteUrlTemplate.replace('__ID__', recipe.id)}" method="POST" class="preview-delete-form">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-sm my-recipe-danger preview-delete-recipe">
                        <i class="fas fa-trash mr-1"></i> Eliminar
                    </button>
                </form>
            `,
            bindFooterActions: (container, recipe) => {
                const editButton = container.querySelector('.preview-edit-recipe');
                if (editButton) {
                    editButton.addEventListener('click', function () {
                        openEditRecipe(recipe.id);
                    });
                }

                const deleteForm = container.querySelector('.preview-delete-form');
                if (deleteForm) {
                    deleteForm.addEventListener('submit', function (event) {
                        if (!window.confirm('¿Estás seguro de querer eliminar esta receta?')) {
                            event.preventDefault();
                        }
                    });
                }
            },
        });

        document.querySelectorAll('.view-recipe-btn[data-recipe-id]').forEach((button) => {
            button.addEventListener('click', function (event) {
                event.preventDefault();

                if (recipePreview) {
                    recipePreview.open(this.getAttribute('data-recipe-id'));
                }
            });
        });

        const successAlert = document.getElementById('successAlert');
        if (successAlert && window.jQuery && typeof window.jQuery.fn.alert === 'function') {
            setTimeout(function () {
                window.jQuery(successAlert).alert('close');
            }, 3000);
        }
    });
</script>
