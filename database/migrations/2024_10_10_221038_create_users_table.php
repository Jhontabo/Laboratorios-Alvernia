<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->bigIncrements('id_usuario');
                $table->string('nombre');
                $table->string('apellido');
                $table->string('correo_electronico')->unique();
                $table->string('telefono')->nullable();
                $table->string('Direccion');
                $table->string('name')->virtualAs('CONCAT(nombre, " ", apellido)');
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
