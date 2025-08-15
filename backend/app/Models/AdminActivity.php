<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAdvancedFilters;

class AdminActivity extends Model
{
    use HasFactory, HasAdvancedFilters;

    protected $fillable = [
        'admin_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'description',
        'severity',
        'tags'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'tags' => 'array'
    ];

    /**
     * Aranabilir alanlar
     */
    protected $searchable = [
        'action',
        'description',
        'model_type',
        'admin.name',
        'ip_address'
    ];

    /**
     * Admin kullanıcısı ile ilişki
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * İlgili model ile polimorfik ilişki
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Aktivite türlerine göre scope'lar
     */
    public function scopeLogin($query)
    {
        return $query->where('action', 'login');
    }

    public function scopeLogout($query)
    {
        return $query->where('action', 'logout');
    }

    public function scopeCreate($query)
    {
        return $query->where('action', 'create');
    }

    public function scopeUpdate($query)
    {
        return $query->where('action', 'update');
    }

    public function scopeDelete($query)
    {
        return $query->where('action', 'delete');
    }

    public function scopeView($query)
    {
        return $query->where('action', 'view');
    }

    /**
     * Önem derecesine göre scope'lar
     */
    public function scopeHigh($query)
    {
        return $query->where('severity', 'high');
    }

    public function scopeMedium($query)
    {
        return $query->where('severity', 'medium');
    }

    public function scopeLow($query)
    {
        return $query->where('severity', 'low');
    }

    /**
     * Son aktiviteler
     */
    public function scopeRecent($query, $limit = 50)
    {
        return $query->latest()->limit($limit);
    }

    /**
     * Belirli bir admin'in aktiviteleri
     */
    public function scopeByAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    /**
     * Belirli bir model türü için aktiviteler
     */
    public function scopeForModel($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    /**
     * Aktivite açıklamasını formatla
     */
    public function getFormattedDescriptionAttribute()
    {
        if ($this->description) {
            return $this->description;
        }

        $modelName = $this->getModelDisplayName();
        $adminName = $this->admin->name ?? 'Bilinmeyen';

        switch ($this->action) {
            case 'login':
                return "{$adminName} admin paneline giriş yaptı";
            case 'logout':
                return "{$adminName} admin panelinden çıkış yaptı";
            case 'create':
                return "{$adminName} yeni {$modelName} oluşturdu";
            case 'update':
                return "{$adminName} {$modelName} güncelledi";
            case 'delete':
                return "{$adminName} {$modelName} sildi";
            case 'view':
                return "{$adminName} {$modelName} görüntüledi";
            default:
                return "{$adminName} {$this->action} işlemi gerçekleştirdi";
        }
    }

    /**
     * Model adını Türkçe'ye çevir
     */
    private function getModelDisplayName()
    {
        $modelMap = [
            'App\Models\User' => 'kullanıcı',
            'App\Models\BlogPost' => 'blog yazısı',
            'App\Models\Product' => 'ürün',
            'App\Models\ProductCategory' => 'kategori',
            'App\Models\ForumTopic' => 'forum konusu',
            'App\Models\ForumPost' => 'forum mesajı',
            'App\Models\Setting' => 'ayar',
        ];

        return $modelMap[$this->model_type] ?? 'kayıt';
    }

    /**
     * Önem derecesi rengini al
     */
    public function getSeverityColorAttribute()
    {
        switch ($this->severity) {
            case 'high':
                return 'red';
            case 'medium':
                return 'yellow';
            case 'low':
                return 'green';
            default:
                return 'gray';
        }
    }

    /**
     * Önem derecesi etiketini al
     */
    public function getSeverityLabelAttribute()
    {
        switch ($this->severity) {
            case 'high':
                return 'Yüksek';
            case 'medium':
                return 'Orta';
            case 'low':
                return 'Düşük';
            default:
                return 'Normal';
        }
    }

    /**
     * Aktivite ikonunu al
     */
    public function getIconAttribute()
    {
        switch ($this->action) {
            case 'login':
                return 'login';
            case 'logout':
                return 'logout';
            case 'create':
                return 'plus';
            case 'update':
                return 'pencil';
            case 'delete':
                return 'trash';
            case 'view':
                return 'eye';
            default:
                return 'cog';
        }
    }

    /**
     * Değişiklikleri karşılaştır
     */
    public function getChangesAttribute()
    {
        if (!$this->old_values || !$this->new_values) {
            return [];
        }

        $changes = [];
        foreach ($this->new_values as $key => $newValue) {
            $oldValue = $this->old_values[$key] ?? null;
            if ($oldValue !== $newValue) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue
                ];
            }
        }

        return $changes;
    }
}