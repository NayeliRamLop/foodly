﻿﻿@extends(auth()->check() ? 'adminlte::page' : 'layouts.public')


@section('title', 'Inicio - Cocina con Gusto')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/custom-public.css') }}">
    @include('partials.recipe-ui-styles')
    <style>
        body {
            background-size: cover;
            background-image: url('/images/fondo-04.jpg');
            background-position: center;
            background-attachment: fixed;
        }

        .wrapper,
        .content-wrapper {
          background: transparent !important;
        }

        /* Home autenticado: navbar pegado arriba */
        .wrapper {
          margin-top: 0 !important;
          padding-top: 0 !important;
        }

        .wrapper > .main-header.navbar {
          position: fixed !important;
          top: 0 !important;
          left: 0 !important;
          right: 0 !important;
          width: 100% !important;
          margin-top: 0 !important;
          z-index: 1040 !important;
        }

        .content-wrapper {
          background-image: url('/images/fondo-04.jpg');
          background-size: cover;
          background-position: center;
          background-attachment: fixed;
        }

        .content-wrapper > .content {
          padding: 0 !important;
        }

        /* Logo */
        .logo-container {
            position: static;
            z-index: 10;
        }

        .logo-container img {
          height: 120px;
            width: auto;
        }

        /* Typewriter Container */
        .typewriter-container {
            position: static;
          width: min(1100px, 86vw);
            min-height: 3.4em;
            height: auto;
            max-height: none;
            font-size: 2.0rem;
            white-space: normal;
            overflow: visible;
            padding: 20px;
            color: rgb(75, 75, 75);
            font-weight: bold;
            letter-spacing: 0.05em;
            text-align: left;
            z-index: 10;
            font-family: 'Anonymous Pro', monospace;
        }

        .hero-section {
            background: transparent;
            padding: 10px 40px 16px !important;
            min-height: auto;
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 3rem;
          max-width: 1280px;
          margin: 0 auto;
        }

        @auth
        .hero-section {
          padding-top: 88px !important;
        }
        @endauth

        .carousel-fullwidth {
            margin-top: 0.2rem;
            width: min(1460px, 98vw);
            margin-left: auto;
            margin-right: auto;
        }

        #homeCarousel .carousel-inner {
          max-height: 560px;
          border-radius: 26px;
          overflow: hidden;
        }

        #homeCarousel .carousel-item {
          height: 560px;
        }

        #homeCarousel .carousel-item img {
          height: 560px;
          object-fit: cover;
          object-position: center;
        }

        .carousel-photo-slide::after {
          content: "";
          position: absolute;
          inset: 0;
          background: linear-gradient(180deg, rgba(34, 22, 13, 0.10) 0%, rgba(34, 22, 13, 0.34) 100%);
          pointer-events: none;
        }

        .carousel-caption-box {
          position: absolute;
          left: 2rem;
          bottom: 2rem;
          z-index: 2;
          max-width: 560px;
          padding: 1.35rem 1.5rem;
          border-radius: 24px;
          background: rgba(255, 251, 247, 0.56);
          backdrop-filter: blur(10px);
          box-shadow: 0 18px 34px rgba(63, 38, 12, 0.10);
          border: 1px solid rgba(255, 255, 255, 0.38);
        }

        .carousel-caption-box.caption-right {
          left: auto;
          right: 2rem;
          text-align: right;
        }

        .carousel-caption-box.caption-center {
          left: 50%;
          right: auto;
          transform: translateX(-50%);
          text-align: center;
        }

        .carousel-caption-box.caption-center .carousel-caption-kicker,
        .carousel-caption-box.caption-center .carousel-caption-title,
        .carousel-caption-box.caption-center .carousel-caption-text {
          justify-content: center;
          text-align: center;
        }

        .carousel-caption-box.caption-right .carousel-caption-kicker {
          justify-content: flex-end;
          width: 100%;
        }

        .carousel-caption-kicker {
          display: inline-flex;
          align-items: center;
          gap: 0.45rem;
          margin-bottom: 0.6rem;
          color: #b15d2f;
          font-size: 0.8rem;
          font-weight: 700;
          letter-spacing: 0.04em;
          text-transform: uppercase;
        }

        .carousel-caption-title {
          margin: 0 0 0.35rem;
          color: #7c4a1c;
          font-size: clamp(1.7rem, 2.4vw, 2.55rem);
          line-height: 1.08;
          font-weight: 800;
          text-wrap: balance;
        }

        .carousel-caption-text {
          margin: 0;
          color: #67584c;
          font-size: 1rem;
          line-height: 1.65;
        }

        .game-carousel-slide {
          background:
            linear-gradient(90deg, rgba(255, 248, 241, 0.72) 0%, rgba(255, 248, 241, 0.60) 100%),
            url('{{ asset('images/fondo-04.jpg') }}') center/cover no-repeat;
        }

        .game-slide-content {
          height: 100%;
          display: grid;
          grid-template-columns: minmax(0, 1fr) minmax(0, 1.1fr);
          align-items: center;
          gap: 2rem;
          padding: 2.5rem 3.2rem;
        }

        .game-slide-brand {
          display: flex;
          align-items: center;
          justify-content: center;
          height: 100%;
          padding: 1rem;
        }

        .game-slide-brand img {
          display: block;
          width: auto;
          height: auto;
          max-width: 100%;
          max-height: 410px;
          object-fit: contain;
          filter: drop-shadow(0 18px 34px rgba(87, 52, 20, 0.16));
        }

        .game-slide-copy {
          color: #5b4537;
          max-width: 560px;
          padding: 1.35rem 1.5rem;
          border-radius: 24px;
          background: rgba(255, 251, 247, 0.56);
          backdrop-filter: blur(10px);
          border: 1px solid rgba(255, 255, 255, 0.38);
          box-shadow: 0 18px 34px rgba(63, 38, 12, 0.10);
        }

        .game-slide-kicker {
          display: inline-flex;
          align-items: center;
          gap: 0.45rem;
          padding: 0.48rem 0.9rem;
          border-radius: 999px;
          background: rgba(255, 255, 255, 0.86);
          border: 1px solid #f1d6c0;
          color: #b15d2f;
          font-size: 0.82rem;
          font-weight: 700;
          margin-bottom: 1rem;
        }

        .game-slide-title {
          margin: 0 0 0.9rem;
          color: #7c4a1c;
          font-size: clamp(2.1rem, 3vw, 3.1rem);
          line-height: 1.06;
          font-weight: 800;
          text-wrap: balance;
        }

        .game-slide-text {
          margin: 0 0 1.4rem;
          font-size: 1.04rem;
          line-height: 1.75;
          color: #6d5b4d;
          max-width: 50ch;
        }

        .game-slide-actions {
          display: flex;
          align-items: center;
          gap: 1rem;
          flex-wrap: wrap;
        }

        .game-slide-button {
          display: inline-flex;
          align-items: center;
          justify-content: center;
          gap: 0.45rem;
          min-width: 170px;
          padding: 0.85rem 1.4rem;
          border-radius: 999px;
          background: linear-gradient(135deg, var(--primary) 0%, #d56d36 100%);
          color: #fff !important;
          font-size: 1rem;
          font-weight: 700;
          text-decoration: none;
          box-shadow: 0 14px 28px rgba(213, 109, 54, 0.24);
        }

        .game-slide-button:hover {
          color: #fff !important;
          transform: translateY(-2px);
          box-shadow: 0 18px 30px rgba(213, 109, 54, 0.30);
        }

        .game-slide-note {
          color: #8c7662;
          font-size: 0.9rem;
          font-weight: 600;
        }

        .top-recetas {
            background: rgba(255, 255, 255, 0.95);
            margin-top: 3rem;
        }

        .top-recetas .container {
          max-width: 1560px;
          margin: 0 auto;
        }

        .top-recetas h5 {
          text-align: center;
        }

        @media (min-width: 1200px) {
          .col-xl-1-5 {
            flex: 0 0 20%;
            max-width: 20%;
          }
        }

        .recipe-card {
          position: relative;
          min-width: 250px;
          border-radius: 16px;
          border: 1px solid #eee;
          box-shadow: 0 12px 24px rgba(0,0,0,0.06);
          overflow: hidden;
          background: var(--bg-soft, #fff6e9);
          transition: all 0.25s ease;
          max-width: 100%;
        }

        .top-recetas .recipe-card {
          min-width: 0;
          width: 100%;
          max-width: 100%;
          padding: 0;
          margin-left: auto;
          margin-right: auto;
          flex-shrink: 1;
          border-radius: 16px;
          background: var(--bg-soft, #fff6e9);
          height: 100%;
        }

        .popular .recipe-card {
          width: 100%;
          padding: 0;
          margin-right: 0;
          flex-shrink: 1;
          border-radius: 16px;
          background: var(--bg-soft, #fff6e9);
        }

        .recipe-card:hover {
          transform: translateY(-5px);
          box-shadow: 0 10px 20px rgba(var(--primary-rgb, 65,89,29), 0.15);
          border-color: rgba(var(--primary-rgb, 65,89,29), 0.3);
        }

        .recipe-card .image-wrapper {
          height: 200px;
          background-color: #f8f9fa;
          display: flex;
          align-items: center;
          justify-content: center;
          border-top-left-radius: 12px;
          border-top-right-radius: 12px;
          position: relative;
          overflow: hidden;
          padding: 0;
        }

        .recipe-card .btn-favorite {
          position: absolute;
          top: 10px;
          right: 10px;
          background: rgba(255,255,255,0.7);
          border: none;
          border-radius: 50%;
          width: 36px;
          height: 36px;
          padding: 0;
          z-index: 10;
          display: inline-flex;
          align-items: center;
          justify-content: center;
        }

        .recipe-card .btn-favorite i {
          font-size: 1.2rem;
          color: #6c757d;
          transition: color 0.2s ease;
        }

        .recipe-card .btn-favorite:hover i {
          color: #dc3545;
        }

        .recipe-card .img-placeholder {
          height: 200px;
          background-color: #f8f9fa;
          border-top-left-radius: 12px;
          border-top-right-radius: 12px;
          border-bottom: 1px solid #f1c29c;
        }

        .recipe-card .card-title {
          font-size: 1.3rem;
          color: var(--primary);
          margin-bottom: 0.75rem;
          font-weight: 600;
          text-align: center;
        }

        .recipe-card .card-body {
          min-height: 140px;
        }

        .recipe-card .card-text {
          font-size: 1rem;
          margin: 0 0 0.5rem;
          text-align: center;
        }

        .popular {
            background: rgba(255, 255, 255, 0.95);
            margin-top: 2rem;
        }

        .home-legal {
          color: #6f6f6f;
          font-size: 0.95rem;
          font-weight: 400;
          line-height: 1.45;
          letter-spacing: 0.01em;
          margin: 0;
        }

        @media (max-width: 1600px) {
          .typewriter-container {
            width: 760px;
            font-size: 1.8rem;
          }
        }

        @media (max-width: 1200px) {
          .typewriter-container {
            width: 620px;
            font-size: 1.5rem;
          }

          .logo-container img {
            height: 120px;
          }
        }

        @media (max-width: 992px) {
          @auth
          .hero-section {
            padding: 120px 20px 20px !important;
          }
          @endauth

          .hero-section {
            min-height: auto;
            padding: 120px 20px 20px;
            text-align: center;
          }

          .logo-container {
            position: static;
            margin-bottom: 1rem;
          }

          .typewriter-container {
            position: static;
            width: min(92vw, 700px);
            max-height: none;
            font-size: 1.45rem;
            text-align: center;
            padding: 0.5rem 1rem;
            margin: 0 auto;
          }
        }

        @media (max-width: 768px) {
          .hero-section {
            min-height: auto;
            padding: 96px 15px 12px;
          }

          .carousel-fullwidth {
            margin-top: 0.1rem;
            width: min(1460px, 97vw);
          }

          #homeCarousel .carousel-item img {
            height: calc(97vw * 560 / 1460);
            max-height: 560px;
          }

          .carousel-caption-box {
            left: 1rem;
            right: 1rem;
            bottom: 1rem;
            max-width: none;
            padding: 1rem;
            transform: none;
            text-align: left;
          }

          .carousel-caption-box.caption-right,
          .carousel-caption-box.caption-center {
            left: 1rem;
            right: 1rem;
          }

          .carousel-caption-box.caption-right .carousel-caption-kicker,
          .carousel-caption-box.caption-center .carousel-caption-kicker {
            justify-content: flex-start;
            width: auto;
          }

          .game-slide-content {
            grid-template-columns: 1fr;
            text-align: center;
            justify-items: center;
            gap: 1rem;
            padding: 1.8rem 1.4rem;
          }

          .game-slide-copy {
            max-width: 100%;
          }

          .game-slide-actions {
            justify-content: center;
          }

          .game-slide-brand img {
            max-height: 210px;
            transform: scale(1);
          }

          .game-slide-copy {
            padding: 1rem;
          }

          #homeCarousel .carousel-inner,
          #homeCarousel .carousel-item {
            max-height: calc(97vw * 560 / 1460);
            height: calc(97vw * 560 / 1460);
          }

          .top-recetas,
          .popular {
            margin-top: 1.5rem;
          }
        }

        @media (max-width: 576px) {
          main {
            padding-top: 50px;
          }

          .hero-section {
            padding: 78px 10px 10px;
          }

          .logo-container img {
            height: 80px;
          }

          .typewriter-container {
            font-size: 1rem;
            padding: 0.5rem 0.5rem;
            width: 90vw;
          }

          .carousel-fullwidth {
            margin-top: 0;
          }

          .top-recetas .container,
          .popular .container {
            padding-left: 10px;
            padding-right: 10px;
          }

          .top-recetas h5,
          .popular h2 {
            font-size: 1.25rem;
          }
        }

    </style>
@stop

@section('content')
<div class="hero-section">
    <div class="logo-container">
        <img src="{{ asset('images/logo.png') }}" alt="Foodly">
    </div>

    <div class="typewriter-container" id="typewriter">
Cocina con gusto con ingredientes simples, comparte experiencias culinarias únicas y descubre nuevas recetas cada día.
    </div>
</div>

    <!-- Carrusel de recetas (full width) -->
    <div class="carousel-fullwidth">
        <div id="homeCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="2"></button>
                <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="3"></button>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active carousel-photo-slide">
                    <img src="{{ asset('images/carousel/slide1.jpg') }}" class="d-block w-100" alt="Nuevas Recetas">
                    <div class="carousel-caption-box">
                        <div class="carousel-caption-kicker">
                            <i class="fas fa-fire"></i> Nuevo antojo favorito
                        </div>
                        <h3 class="carousel-caption-title">Recetas que despiertan el hambre con solo verlas.</h3>
                        <p class="carousel-caption-text">Encuentra propuestas irresistibles para cocinar algo especial sin complicarte de más.</p>
                    </div>
                </div>
                <div class="carousel-item carousel-photo-slide">
                    <img src="{{ asset('images/carousel/slide2.jpg') }}" class="d-block w-100" alt="Cocina con Gusto">
                    <div class="carousel-caption-box caption-right">
                        <div class="carousel-caption-kicker">
                            <i class="fas fa-utensils"></i> Sabor que conquista
                        </div>
                        <h3 class="carousel-caption-title">Haz que cada comida se sienta más rica, cálida y memorable.</h3>
                        <p class="carousel-caption-text">Descubre combinaciones llenas de personalidad para convertir lo cotidiano en algo delicioso.</p>
                    </div>
                </div>
                <div class="carousel-item carousel-photo-slide">
                    <img src="{{ asset('images/carousel/slide3.jpg') }}" class="d-block w-100" alt="Técnicas Profesionales">
                    <div class="carousel-caption-box caption-center">
                        <div class="carousel-caption-kicker">
                            <i class="fas fa-star"></i> Lleva tu cocina más lejos
                        </div>
                        <h3 class="carousel-caption-title">Aprende técnicas que hacen que tus recetas se vean y sepan mejor.</h3>
                        <p class="carousel-caption-text">Inspírate con ideas más pulidas, creativas y fáciles de aplicar desde el primer intento.</p>
                    </div>
                </div>
                <div class="carousel-item game-carousel-slide" id="foodly-game-slide">
                    <div class="game-slide-content">
                        <div class="game-slide-brand">
                            <img src="{{ asset('images/carousel/slide4.png') }}" alt="Logo del videojuego Foodly">
                        </div>
                        <div class="game-slide-copy">
                            <div class="game-slide-kicker">
                                <i class="fas fa-gamepad"></i> Nuevo juego gratis
                            </div>
                            <h3 class="game-slide-title">Explora una aventura nueva, divertida y totalmente gratuita.</h3>
                            <p class="game-slide-text">
                                Descubre el nuevo juego de Foodly y sumérgete en experiencias dinámicas, retos entretenidos y momentos pensados para disfrutar desde el primer clic.
                            </p>
                            <div class="game-slide-actions">
                                <a href="{{ asset('downloads/Level01.zip') }}" class="game-slide-button" download="Level01.zip">
                                    <i class="fas fa-download"></i> Descargar juego
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        </div>
    </div>


<section class="top-recetas py-5">
  <div class="container">
    <h5 class="mb-4 fw-bold">TOP 5 Recetas</h5>

    <div class="row g-4">
      @forelse($topRecipes as $recipe)
        <div class="col-md-6 col-lg-4 col-xl-1-5">
          @include('partials.recipe-card', ['recipe' => $recipe])
        </div>
      @empty
        <div class="col-12 text-center text-muted">
          No hay recetas disponibles todavía.
        </div>
      @endforelse
    </div>
  </div>
</section>

<section class="popular py-5">
  <div class="container">
    <h2 class="text-center mb-4">Lo más popular</h2>

    <!-- Tabs -->
    <ul class="nav nav-tabs justify-content-center mb-4" role="tablist">
      @foreach($popularSections as $title => $sectionRecipes)
        <li class="nav-item">
          <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" href="#popular-{{ $loop->index }}" role="tab">
            {{ $title }}
          </a>
        </li>
      @endforeach
    </ul>

    <div class="tab-content">
      @foreach($popularSections as $title => $sectionRecipes)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="popular-{{ $loop->index }}" role="tabpanel">
          <div class="row g-4">
            @forelse($sectionRecipes as $recipe)
              <div class="col-md-6 col-lg-3">
                @include('partials.recipe-card', ['recipe' => $recipe])
              </div>
            @empty
              <div class="col-12 text-center text-muted">
                No hay recetas para esta sección.
              </div>
            @endforelse
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

    <div class="container mt-5">
        <div class="cooking-section text-center mb-5">
      <p class="home-legal mb-1">{{ date('Y') }} Foodly® Derechos reservados</p>
        <p class="home-legal">Desarrollado por Software Solutions</p>
        </div>
    </div>

    @include('partials.recipe-preview-modal', [
        'modalId' => 'homeRecipeModal',
        'titleId' => 'homeRecipeModalLabel',
        'bodyId' => 'homeRecipeModalBody',
    ])
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.recipe-preview-modal-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const initialRecipeId = Number(@json(request('open_recipe')));
            // Inicializar carrusel
            var myCarousel = new bootstrap.Carousel(document.getElementById('homeCarousel'), {
                interval: 5000,
                wrap: true
            });

            // Máquina de escribir
            const typewriterEl = document.getElementById('typewriter');
            if (typewriterEl) {
                const fullText = typewriterEl.textContent.trim();
                typewriterEl.style.minHeight = `${typewriterEl.offsetHeight}px`;
                const typeWriter = function () {
                    typewriterEl.textContent = '';

                    let index = 0;
                    const writeNext = function () {
                        if (index < fullText.length) {
                            typewriterEl.textContent += fullText[index];
                            index++;
                            setTimeout(writeNext, 50);
                            return;
                        }

                        setTimeout(typeWriter, 1400);
                    };

                    writeNext();
                };

                typeWriter();
            }

            const recipePreview = window.createRecipePreviewModalController({
                modalId: 'homeRecipeModal',
                bodyId: 'homeRecipeModalBody',
                titleId: 'homeRecipeModalLabel',
                profileBaseUrl: "{{ url('/perfil') }}",
                showUrlTemplate: "{{ route('recipes.show', '__ID__') }}",
                isGuest: @json(!auth()->check()),
                loginUrl: "{{ route('login') }}",
                registerUrl: "{{ route('user.create') }}",
                profilePromptUrl: "{{ route('user.create') }}?intent=profile#crear-cuenta",
                logoUrl: "{{ asset('images/logo.png') }}",
            });

            document.querySelectorAll('.view-recipe-btn[data-recipe-id]').forEach((button) => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();

                    if (recipePreview) {
                        recipePreview.open(this.getAttribute('data-recipe-id'));
                    }
                });
            });

            if (initialRecipeId && recipePreview) {
                recipePreview.open(initialRecipeId);
            }
        });
    </script>
@stop
