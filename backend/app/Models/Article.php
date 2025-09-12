<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Catalog;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Article extends Model
{
    /**
 * @use HasFactory<\Database\Factories\ArticleFactory>
*/
    use HasFactory;
    //use HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'slug',
        'catalog_id',
        'html_content',
        'title'
    ];

    protected $casts = [
        'id' => 'string', // важно для UUID
        'title' => 'string',
        'slug' => 'string',
        'html_content' => 'string',
        'catalog_id' => 'string'
    ];

 //   public function catalog(): BelongsTo
 //   {
 //       return $this->belongsTo(Catalog::class);
//    }

     protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid()->toString();
            }
        });
    }
}
