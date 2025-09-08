<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Catalog;

class Article extends Model
{
    /**
 * @use HasFactory<\Database\Factories\ArticleFactory>
*/
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'catalog_id',
        'html_content',
        'title'
    ];

    public function catalog(): BelongsTo
    {
        return $this->belongsTo(Catalog::class);
    }
}
