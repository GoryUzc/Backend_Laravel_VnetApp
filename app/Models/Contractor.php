<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Franchises;

class Contractor extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'legal_name',
        'rif',
        'name',
        'phone',
        'email',
        'franchise_id',
        'address'
    ];
    
    /**
     * Un contractor (empresa) tiene muchos usuarios (técnicos y personal)
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    
    /**
     * Un contractor pertenece a una franquicia
     */
    public function franchise()
    {
        return $this->belongsTo(Franchises::class);
    }
}