@extends(auth()->check() ? 'adminlte::page' : 'layouts.public')


@section('title', 'Inicio - Cocina con Gusto')

@section('content_header')
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('content')
<div class="hero-section">
    <div class="hero-content">

        <div class="hero-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Foodly Logo">
        </div>

        <div class="hero-text">
            <h2>Cocina con gusto</h2>
            <p>
                 Con ingredientes simples y un poco de creatividad, puedes crear recetas que sorprenden y se comparten.
            </p>
        </div>

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
                    <div class="carousel-caption d-none d-md-block">
                        <h2 class="carousel-title">Descubre nuevas creaciones</h2>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/carousel/slide2.jpg') }}" class="d-block w-100" alt="Cocina con Gusto">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/carousel/slide3.jpg') }}" class="d-block w-100" alt="Técnicas Profesionales">
                    <div class="carousel-caption d-none d-md-block">
                        <h2 class="carousel-title">Técnicas Profesionales</h2>
                        <p class="carousel-subtitle">Domina los secretos de los chefs más experimentados</p>
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
        });
    </script>
@stop
