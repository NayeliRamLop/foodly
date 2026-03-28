@extends('adminlte::page')

@section('title', 'ANALÍTICA ADMIN - COCINA CON GUSTO')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="m-0 text-dark" style="font-size: 2.4rem;">
                <i class="fas fa-chart-line mr-2" style="color: #F28241;"></i>ANALÍTICA GENERAL
            </h1>
            <p class="text-muted mb-0 mt-2">{{ $periodLabel }}</p>
        </div>

        <form method="GET" action="{{ route('admin.analytics.index') }}" class="mt-3 mt-md-0 analytics-filter-form">
            <div class="btn-group btn-group-toggle filter-group" data-toggle="buttons">
                @foreach (['day' => 'Día', 'week' => 'Semana', 'month' => 'Mes', 'year' => 'Año'] as $value => $label)
                    <label class="btn btn-filter {{ $selectedPeriod === $value ? 'active' : '' }}">
                        <input
                            type="radio"
                            name="period"
                            value="{{ $value }}"
                            autocomplete="off"
                            {{ $selectedPeriod === $value ? 'checked' : '' }}
                            onchange="this.form.submit()"
                        >
                        {{ $label }}
                    </label>
                @endforeach
            </div>

            <div class="specific-filter mt-3">
                @if ($selectedPeriod === 'day')
                    <input type="date" name="day" class="form-control" value="{{ $selectedValue }}">
                @elseif ($selectedPeriod === 'week')
                    <input type="week" name="week" class="form-control" value="{{ $selectedValue }}">
                @elseif ($selectedPeriod === 'month')
                    <input type="month" name="month" class="form-control" value="{{ $selectedValue }}">
                @elseif ($selectedPeriod === 'year')
                    <input type="number" name="year" class="form-control" min="2000" max="2100" step="1" value="{{ $selectedValue }}">
                @endif

                <button type="submit" class="btn btn-apply-filter">Aplicar</button>
            </div>
        </form>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="small-box metric-box">
                <div class="inner">
                    <h3>{{ $summary['registeredUsers'] }}</h3>
                    <p>Personas registradas</p>
                </div>
                <div class="icon"><i class="fas fa-user-plus"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box metric-box">
                <div class="inner">
                    <h3>{{ $summary['interactions'] }}</h3>
                    <p>Interacciones en la app</p>
                </div>
                <div class="icon"><i class="fas fa-bolt"></i></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="small-box metric-box">
                <div class="inner">
                    <h3 class="metric-title">{{ $summary['topCategory'] }}</h3>
                    <p>Categoría más popular con {{ $summary['topCategoryInteractions'] }} interacciones</p>
                </div>
                <div class="icon"><i class="fas fa-layer-group"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card analytics-card">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-fire mr-2 text-orange"></i>Categorías de recetas más populares
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="categoriesChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card analytics-card">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-hand-pointer mr-2 text-orange"></i>Interacciones de usuarios en la app
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="interactionsChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card analytics-card">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-2 text-orange"></i>Personas registradas
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="registrationsChart" height="110"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    body {
        background-color: #f5f7fa;
    }

    .analytics-card,
    .metric-box {
        border: 0;
        border-radius: 18px;
        box-shadow: 0 14px 30px rgba(31, 41, 55, 0.08);
        background: linear-gradient(145deg, #ffffff, #f8fafc);
    }

    .metric-box {
        color: #374151;
        min-height: 160px;
        overflow: hidden;
    }

    .metric-box .icon {
        color: rgba(242, 130, 65, 0.18);
    }

    .metric-box .inner {
        padding: 1.5rem;
    }

    .metric-box h3,
    .metric-title {
        color: #1f2937;
        font-weight: 700;
    }

    .metric-title {
        font-size: 1.8rem;
        white-space: normal;
    }

    .filter-group .btn-filter {
        border: 1px solid #f0d0bc;
        background: #fff;
        color: #7c4a2d;
        font-weight: 600;
    }

    .filter-group .btn-filter.active {
        background: #f28241;
        border-color: #f28241;
        color: #fff;
    }

    .analytics-filter-form {
        min-width: 280px;
    }

    .specific-filter {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    .specific-filter .form-control {
        border-radius: 10px;
        border: 1px solid #e6c8b4;
    }

    .btn-apply-filter {
        background: #7c4a2d;
        color: #fff;
        border-radius: 10px;
        padding: 0.5rem 1rem;
        font-weight: 600;
    }

    .btn-apply-filter:hover {
        color: #fff;
        background: #64361f;
    }

    .text-orange {
        color: #f28241;
    }

    @media (max-width: 768px) {
        .specific-filter {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const categoriesData = @json($popularCategoriesChart);
    const interactionsData = @json($interactionsChart);
    const registrationsData = @json($registrationsChart);
    const sharedGridColor = 'rgba(148, 163, 184, 0.18)';

    new Chart(document.getElementById('categoriesChart'), {
        type: 'bar',
        data: {
            labels: categoriesData.labels,
            datasets: [{
                label: 'Interacciones',
                data: categoriesData.values,
                backgroundColor: ['#F28241', '#F7A072', '#9DC08B', '#6C9A8B', '#A8C2E0', '#F2C14E', '#E07A5F', '#81B29A'],
                borderRadius: 10,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y',
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: sharedGridColor }
                },
                y: {
                    grid: { display: false }
                }
            }
        }
    });

    new Chart(document.getElementById('interactionsChart'), {
        type: 'doughnut',
        data: {
            labels: interactionsData.labels,
            datasets: [{
                data: interactionsData.values,
                backgroundColor: ['#F28241', '#9DC08B', '#A8C2E0', '#6C9A8B', '#F2C14E'],
                borderWidth: 0,
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    new Chart(document.getElementById('registrationsChart'), {
        type: 'line',
        data: {
            labels: registrationsData.labels,
            datasets: [{
                label: 'Registros',
                data: registrationsData.values,
                borderColor: '#F28241',
                backgroundColor: 'rgba(242, 130, 65, 0.18)',
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#F28241',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: { color: sharedGridColor }
                }
            }
        }
    });
</script>
@stop
