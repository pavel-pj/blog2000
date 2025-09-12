<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Articles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Catalog extends Model
{
    /**
 * @use HasFactory<\Database\Factories\CatalogFactory>
*/
    use HasFactory;
    use HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',

    ];

    protected $casts = [
        'id' => 'string', // важно для UUID

    ];

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
