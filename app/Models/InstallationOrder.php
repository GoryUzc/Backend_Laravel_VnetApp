<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Meeting;
use App\Models\ProspectAradial;
use App\Models\User;

class InstallationOrder extends Model
{
    use HasFactory;

   /**
    * tabla asociada al modelo
   * @var string
    */
    protected $table = 'installation_order';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'id_meeting',
        'prospect_aradial_id',
        'ont_puerto_1',
        'conector_sc_pc',
        'patch_cord_scpc_scapc',
        'roseta',
        'adapter_scapc',
        'ont_4_puertos',
        'conector_sc_upc',
        'canaletas',
        'ramplug',
        'cable_drop',
        'hilos',
        'potencia_recibida_ont',
        'mac_ont',
        'serial_ont',
        'puerto_nap',
        'ppoe_user',
        'ppoe_password',
        'ubicacion_onu',
        'nro_equipos_conectar',
        'puerto_olt',
        'etiqueta_cliente',
        'router',
        'detalles_instalacion'
    ];
    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected  $casts = [

        'ont_puerto_1' => 'integer',
        'conector_sc_pc' => 'integer',
        'patch_cord_scpc_scapc' => 'integer',
        'roseta' => 'integer',
        'adapter_scapc' => 'integer',
        'ont_4_puertos' => 'integer',
        'conector_sc_upc' => 'integer',
        'canaletas' => 'integer',
        'ramplug' => 'integer',
        'cable_drop' => 'integer',
        'hilos' => 'integer',
        'ppoe_user' => 'string',
        'ppoe_password' => 'string',
    ];

    /**
     * Get the user that owns the installation order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the meeting associated with the installation order.
     */
    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class, 'id_meeting');
    }

    /**
     * Get the prospect Aradial associated with the installation order.
     */
    public function prospectAradial(): BelongsTo
    {
        return $this->belongsTo(ProspectAradial::class, 'prospect_aradial_id');
    }
}
