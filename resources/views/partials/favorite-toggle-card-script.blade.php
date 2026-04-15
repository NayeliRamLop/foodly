<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

        const syncFavoriteButtons = function (favoriteUrl, isFavorite) {
            document.querySelectorAll('.btn-favorite[data-favorite-url]').forEach(function (candidateButton) {
                if (candidateButton.dataset.favoriteUrl !== favoriteUrl) {
                    return;
                }

                candidateButton.classList.toggle('active', !!isFavorite);
                candidateButton.setAttribute('title', isFavorite ? 'Quitar de favoritos' : 'Agregar a favoritos');
                candidateButton.disabled = false;
            });
        };

        const removeFavoriteCardIfNeeded = function (button, isFavorite) {
            if (isFavorite || button.dataset.removeOnInactive !== 'true') {
                return;
            }

            const card = button.closest('[data-favorite-card]') || button.closest('.col-md-6, .col-lg-4, .col-xl-1-5');
            if (card) {
                card.remove();
            }

            const remainingCards = document.querySelectorAll('[data-favorite-card]');
            const emptyState = document.getElementById('favoritesEmptyState');
            const pagination = document.getElementById('favoritesPagination');

            if (!remainingCards.length && emptyState) {
                emptyState.style.display = '';
            }

            if (!remainingCards.length && pagination) {
                pagination.style.display = 'none';
            }
        };

        const toggleFavorite = function (button) {
            if (!button || button.disabled) {
                return;
            }

            const favoriteUrl = button.dataset.favoriteUrl || button.closest('.btn-favorite-form')?.getAttribute('action');
            if (!favoriteUrl) {
                return;
            }

            const previousState = button.classList.contains('active');
            button.disabled = true;
            button.classList.toggle('active', !previousState);
            button.setAttribute('title', !previousState ? 'Quitar de favoritos' : 'Agregar a favoritos');

            fetch(favoriteUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            }).then(function (response) {
                if (!response.ok) {
                    throw new Error('Error al actualizar favoritos.');
                }

                return response.json();
            }).then(function (data) {
                syncFavoriteButtons(favoriteUrl, !!data.is_favorite);
                removeFavoriteCardIfNeeded(button, !!data.is_favorite);
            }).catch(function () {
                button.disabled = false;
                button.classList.toggle('active', previousState);
                button.setAttribute('title', previousState ? 'Quitar de favoritos' : 'Agregar a favoritos');
            });
        };

        document.addEventListener('click', function (event) {
            const button = event.target.closest('.btn-favorite');
            if (!button || !button.dataset.favoriteUrl) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();
            toggleFavorite(button);
        });

        document.addEventListener('submit', function (event) {
            const form = event.target.closest('.btn-favorite-form');
            if (!form) {
                return;
            }

            const button = form.querySelector('.btn-favorite');
            if (!button) {
                return;
            }

            if (!button.dataset.favoriteUrl) {
                button.dataset.favoriteUrl = form.getAttribute('action') || '';
            }

            event.preventDefault();
            toggleFavorite(button);
        });
    });
</script>
