@extends(auth()->check() ? 'adminlte::page' : 'layouts.public')


@section('title', 'Inicio - Cocina con Gusto')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/custom-public.css') }}">
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
            padding: 10px 40px 40px !important;
            min-height: auto;
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 3rem;
          max-width: 1280px;
          margin: 0 auto;
        }

        .carousel-fullwidth {
            margin-top: 2rem;
            width: min(1300px, 96vw);
            margin-left: auto;
            margin-right: auto;
        }

        #homeCarousel .carousel-inner {
          max-height: 487px;
        }

        #homeCarousel .carousel-item {
          height: 487px;
        }

        #homeCarousel .carousel-item img {
          height: 487px;
          object-fit: cover;
          object-position: center;
        }

        .top-recetas {
            background: rgba(255, 255, 255, 0.95);
            margin-top: 3rem;
        }

        .top-recetas .container {
          max-width: 1240px;
          margin: 0 auto;
        }

        .top-recetas h5 {
          text-align: center;
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
          width: 100%;
          max-width: 320px;
          padding: 0;
          margin-left: auto;
          margin-right: auto;
          flex-shrink: 1;
          border-radius: 16px;
          background: var(--bg-soft, #fff6e9);
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

        .recipe-card .btn {
          font-size: 0.95rem;
          padding: 0.45rem 0.9rem;
          border-radius: 8px;
          font-weight: 500;
          transition: all 0.2s;
        }

        .recipe-card .btn:hover {
          transform: translateY(-2px);
        }

        .view-recipe-btn {
          background-color: var(--primary);
          color: #fff;
          border: none;
        }

        .view-recipe-btn:hover {
          background-color: color-mix(in srgb, var(--primary) 85%, black);
          color: #fff;
        }

        .recipe-card .card-footer {
          display: block;
          justify-content: initial;
          align-items: initial;
          margin-top: 0;
          background: #fff;
          padding: 0.6rem 1rem 1rem;
          border: 0;
        }

        .popular {
            background: rgba(255, 255, 255, 0.95);
            margin-top: 2rem;
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
            padding: 100px 15px 20px;
          }

          .carousel-fullwidth {
            margin-top: 1rem;
            width: min(1300px, 97vw);
          }

          #homeCarousel .carousel-item img {
            height: calc(97vw * 487 / 1300);
            max-height: 487px;
          }

          #homeCarousel .carousel-inner,
          #homeCarousel .carousel-item {
            max-height: calc(97vw * 487 / 1300);
            height: calc(97vw * 487 / 1300);
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
            padding: 80px 10px 15px;
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
            margin-top: 1rem;
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
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('images/carousel/slide1.jpg') }}" class="d-block w-100" alt="Nuevas Recetas">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/carousel/slide2.jpg') }}" class="d-block w-100" alt="Cocina con Gusto">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/carousel/slide3.jpg') }}" class="d-block w-100" alt="Técnicas Profesionales">
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

    <div class="row recipes-page">
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 recipe-card">
          <div class="image-wrapper">
            <div class="text-center">
              <i class="fas fa-image fa-3x" style="color: #F28241;"></i>
              <p class="mt-2 mb-0" style="font-size: 1rem;">Sin imagen</p>
            </div>
            <button class="btn-favorite" type="button" aria-label="Favorito">
              <i class="fas fa-heart"></i>
            </button>
          </div>
          <div class="card-body">
            <h5 class="card-title mb-2">Receta 1</h5>
            <p class="card-text text-muted">Descripción breve de la receta destacada.</p>
          </div>
          <div class="card-footer bg-white border-top-0">
            <div class="d-flex justify-content-center">
              <button class="btn btn-sm view-recipe-btn" type="button">
                <i class="fas fa-eye mr-1"></i> Ver
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 recipe-card">
          <div class="image-wrapper">
            <div class="text-center">
              <i class="fas fa-image fa-3x" style="color: #F28241;"></i>
              <p class="mt-2 mb-0" style="font-size: 1rem;">Sin imagen</p>
            </div>
            <button class="btn-favorite" type="button" aria-label="Favorito">
              <i class="fas fa-heart"></i>
            </button>
          </div>
          <div class="card-body">
            <h5 class="card-title mb-2">Receta 2</h5>
            <p class="card-text text-muted">Descripción breve de la receta destacada.</p>
          </div>
          <div class="card-footer bg-white border-top-0">
            <div class="d-flex justify-content-center">
              <button class="btn btn-sm view-recipe-btn" type="button">
                <i class="fas fa-eye mr-1"></i> Ver
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 recipe-card">
          <div class="image-wrapper">
            <div class="text-center">
              <i class="fas fa-image fa-3x" style="color: #F28241;"></i>
              <p class="mt-2 mb-0" style="font-size: 1rem;">Sin imagen</p>
            </div>
            <button class="btn-favorite" type="button" aria-label="Favorito">
              <i class="fas fa-heart"></i>
            </button>
          </div>
          <div class="card-body">
            <h5 class="card-title mb-2">Receta 3</h5>
            <p class="card-text text-muted">Descripción breve de la receta destacada.</p>
          </div>
          <div class="card-footer bg-white border-top-0">
            <div class="d-flex justify-content-center">
              <button class="btn btn-sm view-recipe-btn" type="button">
                <i class="fas fa-eye mr-1"></i> Ver
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 recipe-card">
          <div class="image-wrapper">
            <div class="text-center">
              <i class="fas fa-image fa-3x" style="color: #F28241;"></i>
              <p class="mt-2 mb-0" style="font-size: 1rem;">Sin imagen</p>
            </div>
            <button class="btn-favorite" type="button" aria-label="Favorito">
              <i class="fas fa-heart"></i>
            </button>
          </div>
          <div class="card-body">
            <h5 class="card-title mb-2">Receta 4</h5>
            <p class="card-text text-muted">Descripción breve de la receta destacada.</p>
          </div>
          <div class="card-footer bg-white border-top-0">
            <div class="d-flex justify-content-center">
              <button class="btn btn-sm view-recipe-btn" type="button">
                <i class="fas fa-eye mr-1"></i> Ver
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 recipe-card">
          <div class="image-wrapper">
            <div class="text-center">
              <i class="fas fa-image fa-3x" style="color: #F28241;"></i>
              <p class="mt-2 mb-0" style="font-size: 1rem;">Sin imagen</p>
            </div>
            <button class="btn-favorite" type="button" aria-label="Favorito">
              <i class="fas fa-heart"></i>
            </button>
          </div>
          <div class="card-body">
            <h5 class="card-title mb-2">Receta 5</h5>
            <p class="card-text text-muted">Descripción breve de la receta destacada.</p>
          </div>
          <div class="card-footer bg-white border-top-0">
            <div class="d-flex justify-content-center">
              <button class="btn btn-sm view-recipe-btn" type="button">
                <i class="fas fa-eye mr-1"></i> Ver
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="popular py-5">
  <div class="container">
    <h2 class="text-center mb-4">Lo más popular</h2>

    <!-- Tabs -->
    <ul class="nav nav-tabs justify-content-center mb-4">
      <li class="nav-item">
        <a class="nav-link active" href="#">Regional</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Ingredientes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Marcas de productos</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Tipos de recetas</a>
      </li>
    </ul>

    <!-- Grid recetas -->
    <div class="row g-4">
      <div class="col-md-3">
        <div class="card h-100 recipe-card">
          <div class="image-wrapper">
            <div class="text-center">
              <i class="fas fa-image fa-3x" style="color: #F28241;"></i>
              <p class="mt-2 mb-0" style="font-size: 1rem;">Sin imagen</p>
            </div>
            <button class="btn-favorite" type="button" aria-label="Favorito">
              <i class="fas fa-heart"></i>
            </button>
          </div>
          <div class="card-body">
            <h6 class="card-title mb-2">Receta</h6>
          </div>
          <div class="card-footer bg-white border-top-0 text-center">
            <span>★★★★★</span>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

    <div class="container mt-5">
        <div class="cooking-section text-center mb-5">
            <h3 class="mb-4">Cocina con Gusto es para...</h3>
            <div class="dropping-texts-container">
                <div class="dropping-texts">
                    <div>Principiantes</div>
                    <div>Chefs expertos</div>
                    <div>Amantes de la cocina</div>
                    <div>TODOS!</div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar carrusel
            var myCarousel = new bootstrap.Carousel(document.getElementById('homeCarousel'), {
                interval: 5000,
                wrap: true
            });

            // Máquina de escribir
            const typewriterEl = document.getElementById('typewriter');
            if (typewriterEl) {
                const fullText = typewriterEl.textContent.trim();
                typewriterEl.textContent = '';

                let index = 0;
                function typeWriter() {
                    if (index < fullText.length) {
                        typewriterEl.textContent += fullText[index];
                        index++;
                        setTimeout(typeWriter, 50); // 50ms entre cada letra
                    }
                }

                typeWriter();
            }
        });
    </script>
@stop
