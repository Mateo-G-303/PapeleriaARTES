<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permisos', function (Blueprint $table) {
            $table->id('idper');
            $table->string('nombreper', 50);
            $table->string('descripcionper', 150)->nullable();
            $table->boolean('estadoper')->default(true);
        });

        // Tabla pivote para relación muchos a muchos entre roles y permisos
        Schema::create('rol_permiso', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idrol');
            $table->unsignedBigInteger('idper');
            $table->timestamps();

            $table->foreign('idrol')->references('idrol')->on('roles')->onDelete('cascade');
            $table->foreign('idper')->references('idper')->on('permisos')->onDelete('cascade');
            $table->unique(['idrol', 'idper']);
        });

        // Insertar permisos del sistema
        DB::table('permisos')->insert([
            // Dashboard
            ['idper' => 1, 'nombreper' => 'dashboard.ver', 'descripcionper' => 'Ver Dashboard principal', 'estadoper' => true],
            
            // Productos
            ['idper' => 2, 'nombreper' => 'productos.ver', 'descripcionper' => 'Ver listado de productos', 'estadoper' => true],
            ['idper' => 3, 'nombreper' => 'productos.crear', 'descripcionper' => 'Crear nuevos productos', 'estadoper' => true],
            ['idper' => 4, 'nombreper' => 'productos.editar', 'descripcionper' => 'Editar productos existentes', 'estadoper' => true],
            ['idper' => 5, 'nombreper' => 'productos.eliminar', 'descripcionper' => 'Eliminar productos', 'estadoper' => true],
            
            // Categorías
            ['idper' => 6, 'nombreper' => 'categorias.ver', 'descripcionper' => 'Ver listado de categorías', 'estadoper' => true],
            ['idper' => 7, 'nombreper' => 'categorias.crear', 'descripcionper' => 'Crear nuevas categorías', 'estadoper' => true],
            ['idper' => 8, 'nombreper' => 'categorias.editar', 'descripcionper' => 'Editar categorías existentes', 'estadoper' => true],
            ['idper' => 9, 'nombreper' => 'categorias.eliminar', 'descripcionper' => 'Eliminar categorías', 'estadoper' => true],
            
            // Proveedores
            ['idper' => 10, 'nombreper' => 'proveedores.ver', 'descripcionper' => 'Ver listado de proveedores', 'estadoper' => true],
            ['idper' => 11, 'nombreper' => 'proveedores.crear', 'descripcionper' => 'Crear nuevos proveedores', 'estadoper' => true],
            ['idper' => 12, 'nombreper' => 'proveedores.editar', 'descripcionper' => 'Editar proveedores existentes', 'estadoper' => true],
            ['idper' => 13, 'nombreper' => 'proveedores.eliminar', 'descripcionper' => 'Eliminar proveedores', 'estadoper' => true],
            
            // Ventas
            ['idper' => 14, 'nombreper' => 'ventas.ver', 'descripcionper' => 'Ver listado de ventas', 'estadoper' => true],
            ['idper' => 15, 'nombreper' => 'ventas.crear', 'descripcionper' => 'Registrar nuevas ventas', 'estadoper' => true],
            ['idper' => 16, 'nombreper' => 'ventas.factura', 'descripcionper' => 'Generar facturas', 'estadoper' => true],
            
            // Administración - Usuarios
            ['idper' => 17, 'nombreper' => 'usuarios.ver', 'descripcionper' => 'Ver listado de usuarios', 'estadoper' => true],
            ['idper' => 18, 'nombreper' => 'usuarios.crear', 'descripcionper' => 'Crear nuevos usuarios', 'estadoper' => true],
            ['idper' => 19, 'nombreper' => 'usuarios.editar', 'descripcionper' => 'Editar usuarios existentes', 'estadoper' => true],
            ['idper' => 20, 'nombreper' => 'usuarios.eliminar', 'descripcionper' => 'Eliminar usuarios', 'estadoper' => true],
            
            // Administración - Roles
            ['idper' => 21, 'nombreper' => 'roles.ver', 'descripcionper' => 'Ver listado de roles', 'estadoper' => true],
            ['idper' => 22, 'nombreper' => 'roles.crear', 'descripcionper' => 'Crear nuevos roles', 'estadoper' => true],
            ['idper' => 23, 'nombreper' => 'roles.editar', 'descripcionper' => 'Editar roles existentes', 'estadoper' => true],
            ['idper' => 24, 'nombreper' => 'roles.eliminar', 'descripcionper' => 'Eliminar roles', 'estadoper' => true],
            
            // Administración - Configuraciones
            ['idper' => 25, 'nombreper' => 'configuraciones.ver', 'descripcionper' => 'Ver configuraciones del sistema', 'estadoper' => true],
            ['idper' => 26, 'nombreper' => 'configuraciones.editar', 'descripcionper' => 'Modificar configuraciones', 'estadoper' => true],
        ]);

        // Asignar TODOS los permisos al rol Administrador (idrol = 1)
        $permisos = DB::table('permisos')->pluck('idper');
        foreach ($permisos as $idper) {
            DB::table('rol_permiso')->insert([
                'idrol' => 1,
                'idper' => $idper,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Asignar permisos básicos al rol Empleado (idrol = 2)
        $permisosEmpleado = [1, 2, 6, 10, 14, 15, 16]; // Dashboard, ver productos, ver categorías, ver proveedores, ventas
        foreach ($permisosEmpleado as $idper) {
            DB::table('rol_permiso')->insert([
                'idrol' => 2,
                'idper' => $idper,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Asignar permisos de solo lectura al rol Auditor (idrol = 3)
        $permisosAuditor = [1, 2, 6, 10, 14]; // Solo ver
        foreach ($permisosAuditor as $idper) {
            DB::table('rol_permiso')->insert([
                'idrol' => 3,
                'idper' => $idper,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('rol_permiso');
        Schema::dropIfExists('permisos');
    }
};
