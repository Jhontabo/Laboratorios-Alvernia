<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Categoria;
use App\Models\Reserva;
use App\Models\Horario;
use App\Models\Laboratorio;
use App\Models\Permiso;
use App\Models\Producto;
use App\Models\Rol;
use App\Models\SolicitudReserva;
use App\Models\User;
use App\Policies\CategoriaPolicy;
use App\Policies\ReservaPolicy;
use App\Policies\HorarioPolicy;
use App\Policies\LaboratorioPolicy;
use App\Policies\PermisoPolicy;
use App\Policies\ProductoPolicy;
use App\Policies\RolPolicy;
use App\Policies\SolicitudesReservasPolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Categoria::class => CategoriaPolicy::class,
        Reserva::class => ReservaPolicy::class,
        Horario::class => HorarioPolicy::class,
        Laboratorio::class => LaboratorioPolicy::class,
        Permiso::class => PermisoPolicy::class,
        Producto::class => ProductoPolicy::class,
        Rol::class => RolPolicy::class,
        SolicitudReserva::class => SolicitudesReservasPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        // No es necesario definir gates por ahora.
    }
}
