<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Carbon\Carbon;

trait HasAdvancedFilters
{
    /**
     * Gelişmiş filtreleme uygula
     */
    public function scopeAdvancedFilter(Builder $query, Request $request): Builder
    {
        // Tarih aralığı filtresi
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Durum filtresi
        if ($request->filled('status')) {
            $status = $request->status;
            if (in_array($status, ['active', 'inactive'])) {
                $query->where('is_active', $status === 'active');
            } elseif (in_array($status, ['published', 'draft'])) {
                $query->where('status', $status);
            }
        }

        // Kategori filtresi
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Yazar filtresi
        if ($request->filled('author_id')) {
            $query->where('user_id', $request->author_id);
        }

        // Fiyat aralığı filtresi
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Sıralama
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSortFields = [
            'created_at', 'updated_at', 'name', 'title', 'price', 'status'
        ];

        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        }

        return $query;
    }

    /**
     * Hızlı tarih filtreleri
     */
    public function scopeQuickDateFilter(Builder $query, string $period): Builder
    {
        $now = Carbon::now();

        switch ($period) {
            case 'today':
                $query->whereDate('created_at', $now->toDateString());
                break;
            case 'yesterday':
                $query->whereDate('created_at', $now->subDay()->toDateString());
                break;
            case 'this_week':
                $query->whereBetween('created_at', [
                    $now->startOfWeek()->toDateTimeString(),
                    $now->endOfWeek()->toDateTimeString()
                ]);
                break;
            case 'last_week':
                $query->whereBetween('created_at', [
                    $now->subWeek()->startOfWeek()->toDateTimeString(),
                    $now->subWeek()->endOfWeek()->toDateTimeString()
                ]);
                break;
            case 'this_month':
                $query->whereMonth('created_at', $now->month)
                      ->whereYear('created_at', $now->year);
                break;
            case 'last_month':
                $query->whereMonth('created_at', $now->subMonth()->month)
                      ->whereYear('created_at', $now->subMonth()->year);
                break;
            case 'this_year':
                $query->whereYear('created_at', $now->year);
                break;
            case 'last_year':
                $query->whereYear('created_at', $now->subYear()->year);
                break;
        }

        return $query;
    }

    /**
     * Arama filtresi
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        $searchableFields = $this->getSearchableFields();

        $query->where(function ($q) use ($search, $searchableFields) {
            foreach ($searchableFields as $field) {
                if (str_contains($field, '.')) {
                    // İlişkili alan araması
                    [$relation, $column] = explode('.', $field);
                    $q->orWhereHas($relation, function ($relationQuery) use ($column, $search) {
                        $relationQuery->where($column, 'LIKE', "%{$search}%");
                    });
                } else {
                    // Doğrudan alan araması
                    $q->orWhere($field, 'LIKE', "%{$search}%");
                }
            }
        });

        return $query;
    }

    /**
     * Aranabilir alanları döndür
     */
    protected function getSearchableFields(): array
    {
        return $this->searchable ?? ['name', 'title', 'description'];
    }

    /**
     * Filtreleme seçeneklerini döndür
     */
    public static function getFilterOptions(): array
    {
        return [
            'date_ranges' => [
                'today' => 'Bugün',
                'yesterday' => 'Dün',
                'this_week' => 'Bu Hafta',
                'last_week' => 'Geçen Hafta',
                'this_month' => 'Bu Ay',
                'last_month' => 'Geçen Ay',
                'this_year' => 'Bu Yıl',
                'last_year' => 'Geçen Yıl',
                'custom' => 'Özel Tarih Aralığı'
            ],
            'sort_options' => [
                'created_at' => 'Oluşturulma Tarihi',
                'updated_at' => 'Güncellenme Tarihi',
                'name' => 'Ad',
                'title' => 'Başlık',
                'price' => 'Fiyat'
            ],
            'sort_orders' => [
                'desc' => 'Azalan',
                'asc' => 'Artan'
            ]
        ];
    }
}