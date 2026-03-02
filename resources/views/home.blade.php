@extends(auth()->check() ? 'adminlte::page' : 'layouts.public')


@section('title', 'Inicio - Cocina con Gusto')

@section('content_header')
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        body {
            background-size: cover;
            background-image: url('/images/fondo-04.jpg');
            background-position: center;
            background-attachment: fixed;
        }

        main {
            padding-top: 60px;
            background: transparent;
        }

        /* Logo */
        .logo-container {
            position: absolute;
          top: 200px;
          left: 300px;
            z-index: 10;
        }

        .logo-container img {
          height: 130px;
            width: auto;
        }

        /* Typewriter Container */
        .typewriter-container {
            position: absolute;
          top: 200px;
          right: 160px;
            width: 900px;
            height: auto;
            max-height: 500px;
            font-size: 2.0rem;
            white-space: normal;
            overflow: hidden;
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
            padding: 40px;
            min-height: 400px;
        }

        .carousel-fullwidth {
            margin-top: 2rem;
        }

        .top-recetas {
            background: rgba(255, 255, 255, 0.95);
            margin-top: 3rem;
        }

        .popular {
            background: rgba(255, 255, 255, 0.95);
            margin-top: 2rem;
        }

        @media (max-width: 1600px) {
          .typewriter-container {
            right: 90px;
            width: 760px;
            font-size: 1.8rem;
          }

          .logo-container {
            left: 90px;
          }
        }

        @media (max-width: 1200px) {
          .typewriter-container {
            top: 210px;
            right: 40px;
            width: 620px;
            font-size: 1.5rem;
          }

          .logo-container {
            top: 225px;
            left: 40px;
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

    <div class="d-flex gap-4 overflow-auto">
      <!-- Card receta -->
      <div class="recipe-card">
        <div class="fav">♡</div>
        <div class="img-placeholder"></div>
        <h6>Receta 1</h6>
        <div class="card-footer">
          <span>★★★★★</span>
        </div>
      </div>

       <!-- Card receta -->
      <div class="recipe-card">
        <div class="fav">♡</div>
        <div class="img-placeholder"></div>
        <h6>Receta 2</h6>
        <div class="card-footer">
          <span>★★★★★</span>
        </div>
      </div>

       <!-- Card receta -->
      <div class="recipe-card">
        <div class="fav">♡</div>
        <div class="img-placeholder"></div>
        <h6>Receta 3</h6>
        <div class="card-footer">
          <span>★★★★★</span>
        </div>
      </div>

       <!-- Card receta -->
      <div class="recipe-card">
        <div class="fav">♡</div>
        <div class="img-placeholder"></div>
        <h6>Receta 4</h6>
        <div class="card-footer">
          <span>★★★★★</span>
        </div>
      </div>

       <!-- Card receta -->
      <div class="recipe-card">
        <div class="fav">♡</div>
        <div class="img-placeholder"></div>
        <h6>Receta 5</h6>
        <div class="card-footer">
          <span>★★★★★</span>
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
        <div class="recipe-card">
          <div class="fav">♡</div>
          <div class="img-placeholder"></div>
          <h6>Receta</h6>
          <div class="card-footer">
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
