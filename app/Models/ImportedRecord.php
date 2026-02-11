<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportedRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_mapping_id',
        'lat',
        'lng',
        'attributes',
    ];

    protected function casts(): array
    {
        return [
            'attributes' => 'array',
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
        ];
    }

    public function fieldMapping(): BelongsTo
    {
        return $this->belongsTo(FieldMapping::class);
    }
}
