<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class ProspectAradial extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prospect_aradial';


    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

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
        'otp',
        'email_verified_at',
        'franchise_id',  // Added franchise_id to fillable
    ];

    /**
     * Relationship with franchise
     */
    public function franchise()
    {
        return $this->belongsTo(\App\Models\Franchises::class);
    }
}