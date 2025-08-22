<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Contractor extends Model
{
    use HasFactory;
    protected $fillable = [
      'legal_name',
      'rif',
      'name',
      'phone',
      'email',
      'city',
      'adsress'
    ];
    /**
     * Cnontratista lo tienen muchos usuarios 
     */
    public function users(){
        return $this->hasMany(User::class);
    }
}