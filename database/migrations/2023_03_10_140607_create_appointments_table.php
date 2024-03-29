<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointment_id');
            $table->unsignedBigInteger('schedule_id');
            $table->foreign('schedule_id')->references('schedule_id')->on('schedules')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->date('appointment_date')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('is_notify')->default(0);

            $table->tinyInteger('is_archived')->default(0);

            $table->tinyInteger('is_arrived')->default(0);
            $table->dateTime('arrival_datetime')->nullable();

            $table->tinyInteger('is_served')->default(0);
            $table->dateTime('served_datetime')->nullable();

            $table->tinyInteger('is_noshow')->default(0);
            $table->dateTime('now_show_datetime')->nullable();

            $table->tinyInteger('is_walkin')->default(0);
            
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
        Schema::dropIfExists('appointments');
    }
}
