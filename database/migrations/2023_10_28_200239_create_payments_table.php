<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('dni');
            $table->string('user_name');
            $table->time('payment_time'); // Cambiar a tipo TIME
            $table->date('payment_date');
            $table->text('observation')->nullable();
            $table->decimal('importe', 8, 2);
            $table->string('operation_id'); // Identificador de la operación de caja
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
