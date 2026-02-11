<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FieldMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'file_path',
        'lat_column',
        'lng_column',
        'address_column',
        'attribute_columns',
    ];

    protected function casts(): array
    {
        return [
            'attribute_columns' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function importedRecords(): HasMany
    {
        return $this->hasMany(ImportedRecord::class);
    }
}
