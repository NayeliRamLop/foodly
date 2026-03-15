<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $recipe->recipe_title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 10px; 
            color: #333;
            font-size: 13px; 
            line-height: 1.4; 
        }
        .header {
            text-align: center;
            margin-bottom: 10px; 
        }
        .recipe-title {
            font-size: 20px; 
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px; 
        }
        .recipe-meta {
            font-size: 12px; 
            color: #666;
            margin-bottom: 10px; 
        }
        .recipe-image {
            text-align: center;
            margin: 5px 0;
        }
        .recipe-image img {
            max-width: 250px; 
            max-height: 150px; 
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .section {
            margin: 10px 0; 
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 16px; 
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 1px solid #3498db; 
            padding-bottom: 3px;
            margin-bottom: 8px; 
        }
        .description {
            font-size: 13px;
            line-height: 1.4;
            text-align: justify;
            margin-bottom: 5px;
        }
        .ingredients {
            font-size: 13px;
            line-height: 1.4;
            margin-top: 5px;
        }
        .instructions {
            font-size: 13px;
            line-height: 1.4;
            text-align: justify;
        }
        .footer {
            margin-top: 15px; 
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 11px;
            color: #666;
        }
        .difficulty {
            display: inline-block;
            padding: 2px 6px; 
            border-radius: 3px;
            font-size: 11px; 
            font-weight: bold;
        }
        .difficulty-facil { background-color: #d4edda; color: #155724; }
        .difficulty-media { background-color: #fff3cd; color: #856404; }
        .difficulty-dificil { background-color: #f8d7da; color: #721c24; }
        .compact-container {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="recipe-title">{{ $recipe->recipe_title }}</h1>
        <div class="recipe-meta">
            <strong>Tiempo:</strong> {{ $recipe->preparation_time }} min | 
            <strong>Dificultad:</strong> 
            <span class="difficulty difficulty-{{ strtolower($recipe->difficulty) }}">
                {{ $recipe->difficulty }}
            </span>
            @if($recipe->category)
                | <strong>Categoría:</strong> {{ $recipe->category->name }}
            @endif
            @if($recipe->subcategory)
                | <strong>Subcat.:</strong> {{ $recipe->subcategory->name }}
            @endif
        </div>
    </div>

    <div class="compact-container">
        @if($recipe->image)
            <div class="recipe-image">
                <img src="{{ storage_path('app/public/' . $recipe->image) }}" 
                     alt="{{ $recipe->recipe_title }}"
                     onerror="this.style.display='none'">
            </div>
        @endif

        <div class="section">
            <h2 class="section-title">Descripción</h2>
            <div class="description">
                {{ $recipe->recipe_description }}
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Ingredientes</h2>
            <div class="ingredients">
                {!! nl2br(e($recipe->ingredients)) !!}
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Instrucciones</h2>
            <div class="instructions">
                {!! nl2br(e($recipe->instructions)) !!}
            </div>
        </div>
    </div>

    <div class="footer">
        <p>
            <strong>Autor:</strong> {{ $recipe->user->name ?? 'Usuario' }} | 
            <strong>Generado:</strong> {{ date('d/m/Y H:i') }}
        </p>
    </div>
</body>
</html>