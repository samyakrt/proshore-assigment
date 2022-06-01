<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('address',200);
            $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('manager_id')->nullable()->constrained('employees');
            $table->foreignId('role_id')->constrained('roles');
            $table->date('join_date')->default(now());
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
