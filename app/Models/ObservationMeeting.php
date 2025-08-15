<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\ProspectAradial;
use App\Models\Meeting;

class ObservationMeeting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'prospect_aradial_id',
        'meeting_id',
        'status_meeting',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status_meeting' => 'string',
];
        /**
         * Relación: Una observación de reunión pertenece a un usuario.
         *
         */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación: Una observación de reunión pertenece a un prospecto Aradial.
     */
    public function prospectAradial(): BelongsTo
    {
        return $this->belongsTo(ProspectAradial::class, 'prospect_aradial_id');
    }

    /**
     * Relación: Una observación de reunión pertenece a una reunión.
     */
    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class, 'meeting_id');
    }
}