<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;
    protected $table = "clients";

    protected $fillable = [

        'dni',
        'nombre',
        'email',
        'telefono', 
        'direccion', 
        'observaciones'
    ];

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class);
    }

    /**
     * Mutaciones de fecha.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];
}
