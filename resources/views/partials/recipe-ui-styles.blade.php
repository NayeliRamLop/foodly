<style>
    .recipe-card {
        position: relative;
        min-width: 250px;
        width: 100%;
        max-width: 320px;
        min-height: 430px;
        margin-left: auto;
        margin-right: auto;
        border-radius: 18px;
        border: 1px solid #f0d7c2;
        box-shadow: 0 14px 30px rgba(87, 52, 20, 0.10);
        overflow: hidden;
        background: linear-gradient(180deg, #fff9f4 0%, #ffffff 100%);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .recipe-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 34px rgba(87, 52, 20, 0.14);
        border-color: #efc29f;
    }

    .recipe-card .image-wrapper {
        height: 210px;
        background: linear-gradient(180deg, #fff2e6 0%, #fffdfb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        padding: 1rem;
        border-bottom: 1px solid #f5dfcf;
    }

    .recipe-card .image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .recipe-card .btn-favorite-form {
        position: absolute;
        top: 0.65rem;
        right: 0.65rem;
        z-index: 2;
    }

    .recipe-card .btn-favorite {
        width: 2.45rem;
        height: 2.45rem;
        border: 1px solid rgba(255, 255, 255, 0.82);
        border-radius: 999px;
        background: rgba(30, 30, 30, 0.26);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.16);
        backdrop-filter: blur(4px);
        transition: background 0.16s ease, color 0.16s ease, border-color 0.16s ease, transform 0.16s ease;
    }

    .recipe-card .btn-favorite:hover {
        color: #fff;
        background: rgba(220, 53, 69, 0.84);
        border-color: rgba(255, 255, 255, 0.92);
        transform: translateY(-1px);
    }

    .recipe-card .btn-favorite.active {
        color: #dc3545;
        background: rgba(255, 255, 255, 0.96);
        border-color: rgba(255, 255, 255, 0.98);
    }

    .recipe-video-preview,
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

    .recipe-video-preview {
        object-fit: cover;
    }

    .recipe-embed-preview iframe {
        width: 100%;
        height: 100%;
        border: 0;
    }

    .recipe-card:hover .recipe-video-preview,
    .recipe-card:hover .recipe-embed-preview {
        opacity: 1;
    }

    .recipe-card .card-body {
        min-height: 158px;
        padding: 1rem 1rem 0.7rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        text-align: center;
    }

    .recipe-card .card-title {
        font-size: 1.12rem;
        color: var(--primary);
        margin-bottom: 0.7rem;
        font-weight: 700;
        line-height: 1.35;
        min-height: 3em;
    }

    .recipe-card .card-text {
        font-size: 0.95rem;
        line-height: 1.55;
        margin: 0 0 0.65rem;
        text-align: center;
        color: #6d7680 !important;
    }

    .recipe-card .recipe-metrics {
        font-size: 0.82rem;
        line-height: 1.5;
        color: #7f8790;
        font-weight: 600;
        min-height: 2.5em;
    }

    .recipe-card .card-footer {
        display: flex;
        justify-content: center;
        align-items: center;
        background: transparent;
        padding: 0 1rem 1.1rem;
        border: 0;
    }

    .view-recipe-btn {
        min-width: 124px;
        border: none;
        border-radius: 999px;
        padding: 0.65rem 1.2rem;
        font-size: 0.95rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--primary) 0%, #d66f38 100%);
        color: #fff !important;
        box-shadow: 0 12px 24px rgba(214, 111, 56, 0.22);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
    }

    .view-recipe-btn:hover {
        color: #fff !important;
        transform: translateY(-2px);
        box-shadow: 0 16px 28px rgba(214, 111, 56, 0.28);
    }

    .recipe-preview-modal .modal-dialog {
        max-width: min(1080px, 94vw);
    }

    .recipe-preview-modal .modal-content {
        border: 0;
        border-radius: 26px;
        overflow: hidden;
        box-shadow: 0 24px 54px rgba(60, 36, 12, 0.22);
        background: linear-gradient(180deg, #fffdfb 0%, #fff8f1 100%);
    }

    .recipe-preview-modal .modal-header {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.1rem 1.35rem;
        border-bottom: 1px solid #f0dccb;
        background: rgba(255, 247, 239, 0.94);
    }

    .recipe-preview-modal .modal-title {
        color: var(--primary);
        font-size: 1.22rem;
        font-weight: 800;
        margin: 0;
        text-align: center;
        width: 100%;
        padding: 0 2.5rem;
    }

    .recipe-preview-modal .modal-header .modal-close,
    .recipe-preview-modal .modal-header button.modal-close,
    .recipe-preview-modal .modal-header .close.modal-close {
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

    .recipe-preview-modal .modal-header .modal-close span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        line-height: 1;
    }

    .recipe-preview-modal .modal-header .modal-close:hover {
        color: #8e461d;
        background: rgba(177, 93, 47, 0.08);
    }

    .recipe-preview-modal .modal-body {
        padding: 1.35rem;
    }

    .recipe-preview-modal .modal-footer {
        padding: 0.8rem 1.35rem 1.1rem;
        border-top: 1px solid #f0dccb;
        background: rgba(255, 247, 239, 0.94);
    }

    .recipe-modal-loading {
        min-height: 320px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .recipe-modal-loading .spinner-border {
        width: 2.75rem;
        height: 2.75rem;
        color: #d66f38;
        border-width: 0.22rem;
    }

    .recipe-modal-media {
        position: relative;
        background: linear-gradient(180deg, #fff4e7 0%, #fffdfb 100%);
        border: 1px solid #f4decd;
        border-radius: 20px;
        padding: 1rem;
        min-height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .recipe-modal-media-content {
        width: 100%;
        text-align: center;
    }

    .recipe-modal-media img {
        width: 100%;
        max-height: 380px;
        object-fit: contain;
    }

    .recipe-preview-modal.modal-media-cover .recipe-modal-media {
        padding: 0;
        min-height: 380px;
        overflow: hidden;
    }

    .recipe-preview-modal.modal-media-cover .recipe-modal-media-content {
        width: 100%;
        height: 100%;
        min-height: 380px;
    }

    .recipe-preview-modal.modal-media-cover .recipe-modal-media img {
        width: 100%;
        height: 100%;
        min-height: 380px;
        max-height: none;
        display: block;
        object-fit: cover;
        object-position: center center;
    }

    .recipe-preview-modal .modal-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 42px;
        height: 42px;
        border: 1px solid #efd0b4;
        border-radius: 999px;
        background: rgba(255, 252, 248, 0.96);
        color: #a85d2d;
        box-shadow: 0 14px 28px rgba(177, 93, 47, 0.12);
        z-index: 2;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s ease, background-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
    }

    .recipe-preview-modal .modal-nav-btn.left {
        left: 1rem;
    }

    .recipe-preview-modal .modal-nav-btn.right {
        right: 1rem;
    }

    .recipe-preview-modal .modal-nav-btn:hover:not(:disabled) {
        transform: translateY(-50%) scale(1.05);
        background: linear-gradient(135deg, #f28241 0%, #dd6f37 100%);
        color: #fff;
        box-shadow: 0 18px 30px rgba(214, 111, 56, 0.22);
    }

    .recipe-preview-modal .modal-nav-btn:disabled {
        opacity: 0.38;
        cursor: not-allowed;
        box-shadow: none;
    }

    .recipe-preview-modal .recipe-modal-title {
        color: #7c4a1c;
        font-size: 2.15rem;
        font-weight: 800;
        line-height: 1.15;
        margin: 1.15rem 0 0.85rem;
        text-align: center;
        text-wrap: balance;
        width: 100%;
    }

    .recipe-preview-modal .recipe-modal-description {
        color: #655a50;
        line-height: 1.75;
        margin-bottom: 0.9rem;
    }

    .recipe-preview-modal .recipe-modal-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.8rem;
        margin-bottom: 1rem;
    }

    .recipe-preview-modal .recipe-modal-author {
        color: #74685c;
        font-size: 0.95rem;
        font-weight: 600;
        width: 100%;
    }

    .recipe-preview-modal .recipe-modal-author a {
        color: #b15d2f;
        font-weight: 700;
    }

    .recipe-preview-modal .recipe-author-link:hover {
        color: #8e461d;
    }

    .recipe-preview-modal .recipe-modal-meta-stats {
        width: 100%;
    }

    .recipe-preview-modal .recipe-modal-badges,
    .recipe-preview-modal .recipe-modal-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
    }

    .recipe-preview-modal .recipe-modal-badges {
        align-items: center;
        justify-content: flex-start;
        gap: 0.7rem;
    }

    .recipe-preview-modal .recipe-modal-tags {
        align-items: flex-start;
        justify-content: flex-start;
        gap: 0.8rem 0.75rem;
        width: 100%;
        margin-top: 0.1rem;
        padding: 0.15rem 0 0.1rem;
    }

    .recipe-modal-preview-wrap {
        position: relative;
        margin-top: 1.15rem;
        max-height: 420px;
        overflow: hidden;
        border-radius: 22px;
    }

    .recipe-modal-preview-wrap::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 120px;
        background: linear-gradient(180deg, rgba(255, 248, 241, 0) 0%, #fff8f1 92%);
        pointer-events: none;
    }

    .recipe-preview-modal.is-guest-preview .recipe-modal-preview-wrap {
        max-height: none;
        overflow: visible;
        border-radius: 0;
        margin-top: 0;
    }

    .recipe-preview-modal.is-guest-preview .recipe-modal-preview-wrap::after {
        display: none;
    }

    .recipe-preview-note {
        margin-top: 0.9rem;
        color: #8b7561;
        font-size: 0.93rem;
        font-weight: 600;
        text-align: center;
    }

    .recipe-preview-modal .recipe-modal-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.58rem 1rem;
        border-radius: 999px;
        background: #fff2e5;
        color: #8d4a21;
        border: 1px solid #efd0b4;
        font-size: 0.83rem;
        font-weight: 700;
        line-height: 1.25;
        max-width: 100%;
        white-space: normal;
    }

    .recipe-preview-modal .recipe-modal-badge.is-accent {
        background: linear-gradient(135deg, #f28241 0%, #dd6f37 100%);
        color: #fff;
        border-color: transparent;
    }

    .recipe-preview-modal .recipe-modal-badge i,
    .recipe-preview-modal .recipe-modal-author i,
    .recipe-preview-modal .modal-title i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        font-size: 0.95em;
    }

    .recipe-section {
        margin-top: 1.15rem;
        padding: 1.1rem 1.2rem;
        border-radius: 18px;
        background: #fff;
        border: 1px solid #f2e2d3;
    }

    .recipe-section-title {
        color: #7c4a1c;
        font-size: 1.05rem;
        font-weight: 800;
        margin-bottom: 0.8rem;
    }

    .recipe-section ul,
    .recipe-section ol {
        margin-bottom: 0;
        color: #655a50;
        line-height: 1.75;
    }

    .comment-item {
        padding-top: 0.9rem;
        margin-top: 0.9rem;
        border-top: 1px solid #f1e3d7;
        color: #655a50;
    }

    .comment-item:first-of-type {
        padding-top: 0;
        margin-top: 0;
        border-top: 0;
    }

    .comment-reactions-row {
        margin-top: 0.65rem;
        display: flex;
        justify-content: flex-end;
    }

    .comment-owner-actions {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        margin-left: auto;
        margin-right: 0.5rem;
    }

    .comment-inline-action {
        width: 1.9rem;
        height: 1.9rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #f1d7c3;
        background: #fff8f2;
        color: #c9783e;
        border-radius: 999px;
        font-size: 0.82rem;
    }

    .comment-inline-action.is-danger {
        color: #d25443;
    }

    .comment-inline-action:hover {
        border-color: #F28241;
        color: #F28241;
    }

    .comment-inline-action.is-danger:hover {
        border-color: #d25443;
        color: #d25443;
    }

    .comment-edit-panel {
        margin-top: 0.75rem;
        padding: 0.75rem;
        border: 1px solid #f1e3d7;
        border-radius: 12px;
        background: #fffaf6;
    }

    .comment-edit-stars {
        display: flex;
        gap: 0.25rem;
        margin-bottom: 0.55rem;
    }

    .comment-edit-star {
        width: 2rem;
        height: 2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #f1c29c;
        background: #fff5ec;
        color: #d9a57f;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 700;
        padding: 0;
    }

    .comment-edit-star.active {
        background: #F28241;
        border-color: #F28241;
        color: #fff;
    }

    .comment-edit-textarea {
        border-radius: 10px;
        border: 1px solid #f0d5be;
        font-size: 0.93rem;
    }

    .comment-edit-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.65rem;
    }

    .comment-save-btn,
    .comment-cancel-btn {
        border: 0;
        border-radius: 999px;
        padding: 0.42rem 0.9rem;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .comment-save-btn {
        background: #F28241;
        color: #fff;
    }

    .comment-cancel-btn {
        background: #f4ebe4;
        color: #8f5b37;
    }

    .comment-empty-note {
        color: #9a8575;
        font-style: italic;
    }

    .comment-reaction-btn {
        border: 1px solid #f1d7c3;
        background: #fff8f2;
        color: #c9783e;
        border-radius: 999px;
        padding: 0.28rem 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.82rem;
        font-weight: 700;
        transition: background 0.16s ease, color 0.16s ease, border-color 0.16s ease;
    }

    .comment-reaction-btn.active {
        background: #F28241;
        color: #fff;
        border-color: #F28241;
    }

    .comment-reaction-btn:hover {
        border-color: #F28241;
        color: #F28241;
    }

    .comment-reaction-btn.active:hover {
        color: #fff;
    }

    .recipe-login-invite {
        margin-top: 1.2rem;
        padding: 1.1rem 1.2rem;
        border-radius: 18px;
        border: 1px solid #f2e2d3;
        background: #fff;
        box-shadow: none;
    }

    .recipe-login-brand {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 0.8rem;
    }

    .recipe-login-brand img {
        width: auto;
        height: 56px;
        max-width: min(180px, 70%);
        object-fit: contain;
        filter: drop-shadow(0 8px 16px rgba(87, 52, 20, 0.10));
    }

    .recipe-login-invite .invite-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.42rem 0.8rem;
        border-radius: 999px;
        background: #fff7f0;
        border: 1px solid #efcfb4;
        color: #b15d2f;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 0.85rem;
    }

    .recipe-login-invite h5 {
        color: #7c4a1c;
        font-size: 1.3rem;
        font-weight: 800;
        margin-bottom: 0.65rem;
    }

    .recipe-login-invite p {
        color: #6b6156;
        line-height: 1.7;
        margin-bottom: 1rem;
        max-width: 720px;
    }

    .recipe-preview-modal.is-guest-preview .recipe-login-invite h5,
    .recipe-preview-modal.is-guest-preview .recipe-login-invite p,
    .recipe-preview-modal.is-guest-preview .recipe-login-caption {
        text-align: left;
    }

    .recipe-login-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: center;
    }

    .recipe-login-actions .invite-action {
        min-width: 180px;
        text-decoration: none;
    }

    .recipe-login-caption {
        margin-top: 0.95rem;
        color: #8b7561;
        font-size: 0.86rem;
        font-weight: 600;
    }

    .recipe-login-actions .invite-secondary {
        min-width: 180px;
        padding: 0.65rem 1.2rem;
        border-radius: 999px;
        border: 1px solid #e3c5ac;
        background: #fff;
        color: #975126;
        font-weight: 700;
        text-align: center;
        text-decoration: none;
    }

    .recipe-login-actions .invite-secondary:hover {
        background: #fff6ef;
        color: #80421d;
    }

    @media (max-width: 576px) {
        .recipe-card {
            max-width: 100%;
            min-height: 410px;
        }

        .recipe-card .image-wrapper {
            height: 190px;
        }

        .recipe-preview-modal .modal-dialog {
            max-width: 96vw;
            margin: 0.75rem auto;
        }

        .recipe-preview-modal .modal-body {
            padding: 1rem;
        }

        .recipe-preview-modal .recipe-modal-title {
            font-size: 1.7rem;
        }

        .recipe-preview-modal .modal-nav-btn {
            width: 38px;
            height: 38px;
        }

        .recipe-preview-modal .modal-nav-btn.left {
            left: 0.7rem;
        }

        .recipe-preview-modal .modal-nav-btn.right {
            right: 0.7rem;
        }

        .recipe-login-actions .invite-action,
        .recipe-login-actions .invite-secondary {
            width: 100%;
        }

        .recipe-preview-modal .recipe-modal-tags {
            gap: 0.65rem 0.6rem;
        }
    }

    .modal-star-row {
        display: flex;
        gap: 0.2rem;
        align-items: center;
        flex-wrap: nowrap;
        min-height: 2.6rem;
    }

    .modal-star {
        width: 2.2rem;
        height: 2.2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #fff5ec;
        border: 1px solid #f1c29c;
        border-radius: 10px;
        font-size: 1.35rem;
        font-weight: 700;
        color: #d9a57f;
        cursor: pointer;
        padding: 0;
        line-height: 1;
        transition: color 0.12s ease, background 0.12s ease, border-color 0.12s ease, transform 0.12s ease;
    }

    .modal-star.active {
        color: #fff;
        background: #F28241;
        border-color: #F28241;
    }

    .modal-star:hover {
        color: #F28241;
        border-color: #F28241;
        transform: translateY(-1px);
    }

    .modal-rating-section {
        padding-top: 1rem;
        margin-top: 0.5rem;
        border-top: 1px solid #f1e3d7;
    }

    .modal-comment-label {
        color: #8f5b37;
        font-size: 0.92rem;
        font-weight: 700;
        margin-bottom: 0.45rem;
        margin-top: 0.15rem;
    }
</style>
