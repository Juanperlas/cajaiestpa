<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeletedPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('deleted_payments', function (Blueprint $table) {
            $table->id();
            $table->string('dni');
            $table->string('user_name');
            $table->time('payment_time');
            $table->date('payment_date');
            $table->text('observation')->nullable();
            $table->decimal('importe', 8, 2);
            $table->string('operation_id');
            $table->text('deletion_reason'); // Campo para la justificación de la eliminación
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deleted_payments');
    }
}
