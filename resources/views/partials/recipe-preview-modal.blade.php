<div class="modal fade recipe-preview-modal {{ $modalClass ?? '' }}" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $titleId }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="{{ $titleId }}">
                    <i class="fas fa-utensils mr-2"></i> Receta Completa
                </h4>
                <button type="button" class="close modal-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="{{ $bodyId }}">
                <div class="recipe-modal-loading">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="recipe-preview-footer-actions"></div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
