<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Articles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Catalog extends Model implements Sortable
{
    /**
 * @use HasFactory<\Database\Factories\CatalogFactory>
*/
    use HasFactory;
    use HasUuids;
    use SortableTrait;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['name', 'order_column'];



    protected $casts = [
        'id' => 'string', // важно для UUID
        'order_column' => 'integer',

    ];

    public $sortable = [
        'order_column_name' => 'order_column',
        'sort_when_creating' => true, // Автоматически устанавливает порядок при создании
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->order_column)) {
                $maxOrder = static::max('order_column') ?? 0;
                $model->order_column = $maxOrder + 100;
            }
        });
    }



/*
    public function parent():BelongsTo {
        return $this->belongsTo(Catalog::class,'parent_id');
    }

    public function parent():HasMany {
        return $this->hasMany(Catalog::class,'parent_id');
    }
*/
 //   public function articles(): HasMany
 //   {
 //       return $this->hasMany(Articles::class);
 //   }
}
