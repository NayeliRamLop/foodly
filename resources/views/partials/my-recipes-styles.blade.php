<style>
    .my-recipes-page {
        padding-bottom: 1.5rem;
    }

    .my-recipes-header {
        gap: 1rem;
    }

    .page-title-my-recipes {
        font-weight: 800;
        color: #f28241;
        letter-spacing: 0.04em;
    }

    .my-recipe-card {
        max-width: 100%;
        min-height: 460px;
    }

    .my-recipe-card .image-wrapper {
        position: relative;
        overflow: hidden;
    }

    .recipe-video-preview {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 0.2s ease;
        pointer-events: none;
        background: #000;
    }

    .recipe-card:hover .recipe-video-preview {
        opacity: 1;
    }

    .recipe-embed-preview {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 0.2s ease;
        pointer-events: none;
        background: #000;
    }

    .recipe-embed-preview iframe {
        width: 100%;
        height: 100%;
        border: 0;
    }

    .recipe-card:hover .recipe-embed-preview {
        opacity: 1;
    }

    .my-recipe-footer {
        display: flex !important;
        justify-content: center;
        align-items: center;
        padding: 0 1rem 1.1rem !important;
    }

    .my-recipe-secondary,
    .my-recipe-danger {
        min-height: 42px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .my-recipe-secondary {
        border: 1px solid #efc39f;
        background: #fff6ef;
        color: #a9572a;
        box-shadow: 0 8px 18px rgba(214, 111, 56, 0.12);
    }

    .my-recipe-secondary:hover {
        color: #8f4a21;
        transform: translateY(-2px);
        box-shadow: 0 12px 22px rgba(214, 111, 56, 0.16);
    }

    .my-recipe-danger {
        border: 1px solid #efc1bf;
        background: #fff4f3;
        color: #b74642;
        box-shadow: 0 8px 18px rgba(183, 70, 66, 0.10);
    }

    .my-recipe-danger:hover {
        color: #983734;
        transform: translateY(-2px);
        box-shadow: 0 12px 22px rgba(183, 70, 66, 0.15);
    }

    .recipe-preview-modal .modal-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.85rem;
        flex-wrap: wrap;
    }

    .recipe-preview-footer-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .recipe-preview-footer-actions form {
        margin: 0;
    }

    .recipe-preview-footer-actions .btn,
    .recipe-preview-footer-actions form .btn {
        min-width: 150px;
    }

    .empty-my-recipes-state {
        border: 1px solid #f2ddcb;
        border-radius: 24px;
        background: linear-gradient(180deg, #fff9f4 0%, #ffffff 100%);
        box-shadow: 0 18px 34px rgba(87, 52, 20, 0.10);
        padding: 3rem 1.5rem;
        text-align: center;
        max-width: 720px;
        margin: 2.25rem auto 0;
    }

    .empty-my-recipes-icon {
        width: 90px;
        height: 90px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        background: #fff2e5;
        color: #d66f38;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }

    .empty-my-recipes-state h3 {
        color: #7c4a1c;
        font-size: 1.6rem;
        font-weight: 800;
        margin-bottom: 0.75rem;
    }

    .empty-my-recipes-state p {
        color: #71665a;
        max-width: 520px;
        margin: 0 auto 1.25rem;
        line-height: 1.7;
    }

    .recipe-manage-modal .modal-dialog {
        max-width: min(1120px, 94vw);
    }

    .recipe-manage-modal .modal-content {
        border: 0;
        border-radius: 26px;
        overflow: hidden;
        box-shadow: 0 24px 54px rgba(60, 36, 12, 0.22);
        background: linear-gradient(180deg, #fffdfb 0%, #fff8f1 100%);
    }

    .recipe-manage-modal .modal-header {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.1rem 1.35rem;
        border-bottom: 1px solid #f0dccb;
        background: rgba(255, 247, 239, 0.94);
    }

    .recipe-manage-modal .modal-title {
        color: var(--primary);
        font-size: 1.22rem;
        font-weight: 800;
        margin: 0;
        text-align: center;
        width: 100%;
        padding: 0 2.5rem;
    }

    .recipe-manage-modal .modal-close {
        position: absolute;
        right: 1.35rem;
        top: 50%;
        transform: translateY(-50%);
        border: 0;
        background: transparent;
        color: #b15d2f;
        font-size: 1.3rem;
        font-weight: 700;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        opacity: 1;
        text-shadow: none;
        padding: 0;
        width: 2rem;
        height: 2rem;
        margin: 0;
        border-radius: 999px;
    }

    .recipe-manage-modal .modal-close:hover {
        color: #8e461d;
        background: rgba(177, 93, 47, 0.08);
    }

    .recipe-manage-modal .modal-body {
        padding: 1.35rem;
    }

    .recipe-manage-modal .modal-footer {
        padding: 0.8rem 1.35rem 1.1rem;
        border-top: 1px solid #f0dccb;
        background: rgba(255, 247, 239, 0.94);
    }

    .recipe-manage-shell {
        border: 1px solid #f3dfd0;
        border-radius: 22px;
        background: #fff;
        box-shadow: 0 12px 28px rgba(87, 52, 20, 0.08);
        padding: 1.2rem;
        height: 100%;
    }

    .recipe-manage-section-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #7c4a1c;
        font-size: 1rem;
        font-weight: 800;
        margin-bottom: 1rem;
    }

    .recipe-manage-modal label {
        color: #7a6b5c;
        font-weight: 700;
        font-size: 0.93rem;
    }

    .recipe-manage-modal .form-control,
    .recipe-manage-modal .custom-file-label,
    .recipe-manage-modal .custom-file-label::after,
    .recipe-manage-modal .custom-file-input,
    .recipe-manage-modal .custom-select {
        font-size: 0.95rem;
    }

    .recipe-manage-modal .form-control,
    .recipe-manage-modal .custom-select,
    .recipe-manage-modal .custom-file-label {
        min-height: 44px;
        border-radius: 14px;
        border: 1px solid #efd9c7;
        box-shadow: none !important;
    }

    .recipe-manage-modal textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .recipe-manage-modal .form-control:focus,
    .recipe-manage-modal .custom-select:focus,
    .recipe-manage-modal .custom-file-input:focus ~ .custom-file-label {
        border-color: #f28241;
        box-shadow: 0 0 0 0.2rem rgba(242, 130, 65, 0.12) !important;
    }

    .recipe-manage-modal .custom-file-label::after {
        content: "Buscar";
        border-left: 1px solid #efd9c7;
        border-radius: 0 14px 14px 0;
        background: #fff5ee;
        color: #995126;
        font-weight: 700;
    }

    .brand-chip-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.65rem;
        margin-top: 0.4rem;
    }

    .brand-chip {
        border: 1px solid #efc39f;
        background: #fff6ef;
        color: #a9572a;
        border-radius: 999px;
        padding: 0.5rem 0.95rem;
        font-size: 0.9rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .brand-chip:hover,
    .brand-chip.active {
        background: linear-gradient(135deg, #f28241 0%, #d66f38 100%);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 10px 20px rgba(214, 111, 56, 0.20);
    }

    .brand-custom-wrap {
        display: none;
    }

    .brand-custom-wrap.is-visible {
        display: block;
    }

    .manage-current-media {
        display: none;
        margin-bottom: 0.9rem;
    }

    .manage-current-media.is-visible {
        display: block;
    }

    .manage-current-media-frame {
        border: 1px solid #f1ded0;
        border-radius: 16px;
        background: linear-gradient(180deg, #fff5eb 0%, #fffdfb 100%);
        min-height: 188px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        padding: 0.85rem;
    }

    .manage-current-media-frame img,
    .manage-current-media-frame video,
    .manage-current-media-frame iframe {
        width: 100%;
        max-height: 220px;
        object-fit: contain;
        border: 0;
        border-radius: 12px;
    }

    .manage-submit-btn {
        min-width: 172px;
    }

    .recipe-manage-modal .modal-dialog {
        max-width: min(1120px, 94vw);
        height: calc(100vh - 1.25rem);
        margin-top: 0.625rem;
        margin-bottom: 0.625rem;
    }

    .recipe-manage-modal .modal-content {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .recipe-manage-modal form {
        min-height: 0;
        display: flex;
        flex: 1 1 auto;
        flex-direction: column;
    }

    .recipe-manage-modal .modal-body {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
        overscroll-behavior: contain;
    }

    @media (max-width: 767.98px) {
        .my-recipe-card {
            min-height: 0;
        }

        .recipe-preview-modal .modal-footer {
            justify-content: center;
        }

        .recipe-preview-footer-actions {
            width: 100%;
            justify-content: center;
        }

        .recipe-manage-modal .modal-body,
        .recipe-manage-modal .modal-header,
        .recipe-manage-modal .modal-footer {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .recipe-manage-shell {
            padding: 1rem;
        }

        .recipe-manage-modal .modal-dialog {
            height: calc(100vh - 0.75rem);
            margin-top: 0.375rem;
            margin-bottom: 0.375rem;
        }
    }

    /* Barra de búsqueda unificada en /recipes, /favorites y /mis-recetas */
    .navbar-search-always .search-form {
        width: 588px;
        max-width: 100%;
    }

    .navbar-search-always .form-control-navbar {
        height: 40px;
        font-size: 0.95rem;
        line-height: 1.5;
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem 0 0 0.375rem;
        width: 100%;
    }

    .navbar-search-always .btn-navbar {
        height: 40px;
        font-size: 0.95rem;
        border-radius: 0 0.375rem 0.375rem 0;
        padding: 0.375rem 0.9rem;
        white-space: nowrap;
    }
</style>
