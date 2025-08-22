<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'aradial_user_id',
        'name',
        'last_name',
        'document',
        'document_type',
        'phone',
        'franchise_id',
        'role_id',
        'contractor_id',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
        ];
    }

    /* Relationships */
    public function franchise()
    {
        return $this->belongsTo(\App\Models\Franchises::class);
    }

    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class);
    }

    public function contractor()
    {
        return $this->belongsTo(\App\Models\Contractor::class, 'contractor_id');
    }
}