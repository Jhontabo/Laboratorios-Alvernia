<?php

// app/Models/Usuario.php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Panel;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Support\Facades\Storage;

// usa esto para production 'class User extends Authenticatable implements FilamentUser'
class User extends Authenticatable implements HasAvatar
{
    use Notifiable, HasRoles;


    // contrato para que solo personas autorizadas puedan acceder al sistema
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : null;
    }


    // Nombre de la tabla en la base de datos
    protected $table = 'users';

    // Clave primaria de la tabla
    protected $primaryKey = 'id_usuario';

    // Atributos asignables en masa
    protected $fillable = [
        'name',
        'apellido',
        'email',
        'telefono',
        'direccion',
        'estado',
        'avatar_url',
    ];

    // Atributos ocultos
    protected $hidden = [
        'remember_token',
    ];

    public $timestamps = true;

    // Si no usas una columna virtual, agrega este accesorio
    // public function getNameAttribute()
    // {
    //     return $this->nombre . ' ' . $this->apellido;
    // }
}
