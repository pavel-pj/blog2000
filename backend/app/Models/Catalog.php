<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Articles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Catalog extends Model
{
    /**
 * @use HasFactory<\Database\Factories\CatalogFactory>
*/
    use HasFactory;

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

    public function articles(): HasMany {
        return $this->hasMany(Articles::class);
    }
    */
}
