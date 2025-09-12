<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Articles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Catalog extends Model
{
    /**
 * @use HasFactory<\Database\Factories\CatalogFactory>
*/
    use HasFactory;
    use HasUuids;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'parent_id'
    ];
/*     
    public function parent():BelongsTo {
        return $this->belongsTo(Catalog::class,'parent_id');
    }

    public function parent():HasMany {
        return $this->hasMany(Catalog::class,'parent_id');
    }
*/
    public function articles(): HasMany {
        return $this->hasMany(Articles::class);
    }

        protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }
     
}
