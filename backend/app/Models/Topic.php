<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Word;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Topic extends Model
{
    use HasUuids;
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'subject_id',
        
    ];

    protected $casts = [
        'id' => 'string', // важно для UUID
        

    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

     public function words(): HasMany
    {
        return $this->hasMany(Word::class);
    }
}
