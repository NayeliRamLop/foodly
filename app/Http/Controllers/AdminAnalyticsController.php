<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AdminAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->query('period', 'week');
        $allowedPeriods = ['day', 'week', 'month', 'year'];

        if (!in_array($period, $allowedPeriods, true)) {
            $period = 'week';
        }

        [$start, $end, $selectedValue] = $this->resolveDateRange($period, $request);

        $interactionTotals = $this->getInteractionTotals($start, $end);
        $popularCategories = $this->getPopularCategories($start, $end);
        $registrationsChart = $this->getRegistrationsChart($period, $start, $end);
        $topCategory = $popularCategories->first();
        $registeredUsers = $this->getRegisteredUsersCount($start, $end);

        return view('admin.analytics.index', [
            'selectedPeriod' => $period,
            'selectedValue' => $selectedValue,
            'periodLabel' => $this->getPeriodLabel($period, $start, $end),
            'summary' => [
                'registeredUsers' => $registeredUsers,
                'interactions' => array_sum($interactionTotals),
                'topCategory' => $topCategory['label'] ?? 'Sin datos',
                'topCategoryInteractions' => $topCategory['value'] ?? 0,
            ],
            'popularCategoriesChart' => [
                'labels' => $popularCategories->pluck('label')->values(),
                'values' => $popularCategories->pluck('value')->values(),
            ],
            'interactionsChart' => [
                'labels' => [
                    'Favoritos',
                    'Calificaciones',
                    'Comentarios',
                    'Seguimientos',
                    'Recetas publicadas',
                ],
                'values' => array_values($interactionTotals),
            ],
            'registrationsChart' => $registrationsChart,
        ]);
    }

    private function resolveDateRange(string $period, Request $request): array
    {
        $now = now();

        if ($period === 'day') {
            $selectedDay = $request->query('day');
            $date = $selectedDay ? Carbon::parse($selectedDay) : $now->copy();

            return [$date->copy()->startOfDay(), $date->copy()->endOfDay(), $date->format('Y-m-d')];
        }

        if ($period === 'week') {
            $selectedWeek = $request->query('week');

            if ($selectedWeek && preg_match('/^(\d{4})-W(\d{2})$/', $selectedWeek, $matches)) {
                $date = Carbon::now()->setISODate((int) $matches[1], (int) $matches[2]);
            } else {
                $date = $now->copy();
                $selectedWeek = $date->format('o-\WW');
            }

            return [$date->copy()->startOfWeek(), $date->copy()->endOfWeek(), $selectedWeek];
        }

        if ($period === 'month') {
            $selectedMonth = $request->query('month');
            $date = $selectedMonth ? Carbon::createFromFormat('Y-m', $selectedMonth) : $now->copy();

            return [$date->copy()->startOfMonth(), $date->copy()->endOfMonth(), $date->format('Y-m')];
        }

        $selectedYear = $request->query('year');
        $year = $selectedYear && preg_match('/^\d{4}$/', $selectedYear) ? (int) $selectedYear : (int) $now->format('Y');
        $date = Carbon::create($year, 1, 1);

        return [$date->copy()->startOfYear(), $date->copy()->endOfYear(), (string) $year];
    }

    private function getPeriodLabel(string $period, Carbon $start, Carbon $end): string
    {
        return match ($period) {
            'day' => 'Día seleccionado: ' . $start->translatedFormat('d \\d\\e F \\d\\e Y'),
            'week' => 'Semana seleccionada: ' . $start->format('d/m/Y') . ' al ' . $end->format('d/m/Y'),
            'month' => 'Mes seleccionado: ' . $start->translatedFormat('F \\d\\e Y'),
            'year' => 'Año seleccionado: ' . $start->format('Y'),
            default => 'Período actual',
        };
    }

    private function getRegisteredUsersCount(Carbon $start, Carbon $end): int
    {
        return $this->getUsersRegistrationMoments()
            ->filter(fn ($date) => $date && $date->betweenIncluded($start, $end))
            ->count();
    }

    private function getInteractionTotals(Carbon $start, Carbon $end): array
    {
        return [
            DB::table('favorites')->whereBetween('created_at', [$start, $end])->count(),
            DB::table('recipe_ratings')->whereBetween('created_at', [$start, $end])->count(),
            DB::table('recipe_comments')->whereBetween('created_at', [$start, $end])->count(),
            DB::table('user_followers')->whereBetween('created_at', [$start, $end])->count(),
            DB::table('recipes')->whereBetween('created_at', [$start, $end])->count(),
        ];
    }

    private function getPopularCategories(Carbon $start, Carbon $end): Collection
    {
        $favorites = DB::table('favorites')
            ->select('recipe_id', DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('recipe_id');

        $ratings = DB::table('recipe_ratings')
            ->select('recipe_id', DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('recipe_id');

        $comments = DB::table('recipe_comments')
            ->select('recipe_id', DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('recipe_id');

        return Categories::query()
            ->leftJoin('recipes', 'categories.id', '=', 'recipes.category_id')
            ->leftJoinSub($favorites, 'favorite_totals', function ($join) {
                $join->on('recipes.id', '=', 'favorite_totals.recipe_id');
            })
            ->leftJoinSub($ratings, 'rating_totals', function ($join) {
                $join->on('recipes.id', '=', 'rating_totals.recipe_id');
            })
            ->leftJoinSub($comments, 'comment_totals', function ($join) {
                $join->on('recipes.id', '=', 'comment_totals.recipe_id');
            })
            ->selectRaw("
                COALESCE(NULLIF(categories.name, ''), 'Sin categoria') as category_label,
                COALESCE(SUM(COALESCE(favorite_totals.total, 0) + COALESCE(rating_totals.total, 0) + COALESCE(comment_totals.total, 0)), 0) as interactions_total
            ")
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('interactions_total')
            ->limit(8)
            ->get()
            ->map(function ($category) {
                return [
                    'label' => $category->category_label,
                    'value' => (int) $category->interactions_total,
                ];
            })
            ->filter(fn ($category) => $category['value'] > 0)
            ->values()
            ->whenEmpty(function () {
                return collect([[
                    'label' => 'Sin interacciones',
                    'value' => 0,
                ]]);
            });
    }

    private function getRegistrationsChart(string $period, Carbon $start, Carbon $end): array
    {
        $users = $this->getUsersRegistrationMoments()
            ->filter(fn ($date) => $date && $date->betweenIncluded($start, $end))
            ->values();

        $labels = [];
        $values = [];

        foreach ($this->generateBuckets($period, $start, $end) as $bucket) {
            $labels[] = $bucket['label'];
            $values[] = $users->filter(function ($date) use ($bucket) {
                return $date->betweenIncluded($bucket['start'], $bucket['end']);
            })->count();
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    private function generateBuckets(string $period, Carbon $start, Carbon $end): array
    {
        if ($period === 'day') {
            return [[
                'label' => $start->format('d/m'),
                'start' => $start->copy()->startOfDay(),
                'end' => $end->copy()->endOfDay(),
            ]];
        }

        if ($period === 'week') {
            return collect(CarbonPeriod::create($start, '1 day', $end))
                ->map(function ($date) {
                    return [
                        'label' => ucfirst($date->locale('es')->translatedFormat('D')),
                        'start' => $date->copy()->startOfDay(),
                        'end' => $date->copy()->endOfDay(),
                    ];
                })->all();
        }

        if ($period === 'month') {
            return collect(CarbonPeriod::create($start, '1 day', $end))
                ->map(function ($date) {
                    return [
                        'label' => $date->format('d'),
                        'start' => $date->copy()->startOfDay(),
                        'end' => $date->copy()->endOfDay(),
                    ];
                })->all();
        }

        return collect(range(1, 12))->map(function ($month) use ($start) {
            $bucketStart = $start->copy()->month($month)->startOfMonth();

            return [
                'label' => ucfirst($bucketStart->locale('es')->translatedFormat('M')),
                'start' => $bucketStart,
                'end' => $bucketStart->copy()->endOfMonth(),
            ];
        })->all();
    }

    private function getUsersRegistrationMoments(): Collection
    {
        return DB::table('users')
            ->get(['registration_date', 'created_at'])
            ->map(function ($user) {
                if (!empty($user->registration_date)) {
                    return Carbon::parse($user->registration_date)->startOfDay();
                }

                return !empty($user->created_at)
                    ? Carbon::parse($user->created_at)
                    : null;
            })
            ->filter();
    }
}
