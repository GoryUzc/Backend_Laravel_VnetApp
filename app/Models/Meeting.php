<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Models\User;
use App\Models\ProspectAradial;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Meeting extends Model {
    use HasFactory, Notifiable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meeting';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prospect_aradial_id',
        'user_id',
        'date_time1',
        'status',
        'latitude',
        'longitude',
    ];
    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected  $casts = [

            'date_time1' => 'datetime',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];

     /**
     * Relación: Una reunión pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
        
    }
    /**
     * Relacion: Una reunión pertenece a un prospecto Aradial.
     */
    public function prospect_aradial(): BelongsTo
    {
        return $this->belongsTo(ProspectAradial::class, 'prospect_aradial_id');
    }
}