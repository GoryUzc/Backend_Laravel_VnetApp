<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Franchises extends Model
{
    use HasFactory;
    protected $fillable = [
        'Frachise',
        'branch_office',
        'franchise_id',
        'is_active',
    ];
    /**
     * Rol lo tienen muchos usuarios 
     */
    public function users(){
        return $this->hasMany(User::class);
    }
}
