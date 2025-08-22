<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProspectAradial extends Model
{
    use HasFactory;

   /**
    * tabla asociada al modelo
   * @var string
    */
    protected $table = 'prospect_aradial';

    /**
     * Clave secundaria externa 
     * @var string
     */
    protected $secundaryKey = 'aradial_id';

    /**
     * Clave secundaria no es auto incremental
     * Viene de un sistema externo
     * @var bool
     */
    public $incrementing = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'aradial_id',
        'name',
        'last_name',
        'document',
        'document_type',
        'phone',
        'address',
        'city',
        'email',
        'plan',
        'status_red',
    ];
}