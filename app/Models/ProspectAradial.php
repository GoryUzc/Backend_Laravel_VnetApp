<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProspectAradial extends Model
{
    use HasFactory;

   /**
    * tabla asociada al modelo
   * @var string
    */
    protected $table = 'prospect_aradial';

    /**
     * Clave primaria externa 
     * @var string
     */
    protected $primaryKey = 'aradial_id';

    /**
     * Clave primaria no es auto incremental
     * Viene de un sistema externo
     * @var bool
     */
    public $incrementing = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var arrays
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
        'ppoe_user',
        'ppoe_password',
        'plan',
        'status_red',
    ];
    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected  $casts = [

            'phone' => 'string',
            'email' => 'string',
            'ppoe_user' => 'string',
            'ppoe_password' => 'string',
        ];
}