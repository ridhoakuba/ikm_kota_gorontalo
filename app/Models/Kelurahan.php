<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelurahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kelurahan',
        'kecamatan',
    ];

    /**
     * Kelurahan memiliki banyak User.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Kelurahan memiliki banyak DataIkm.
     */
    public function dataIkms(): HasMany
    {
        return $this->hasMany(DataIkm::class);
    }
}
