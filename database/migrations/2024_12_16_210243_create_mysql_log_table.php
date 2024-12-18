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
        Schema::create('backend_error_logs', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->text('message');
            $table->json('context');
            $table->integer('level');
            $table->string('level_name');
            $table->string('channel');
            $table->dateTime('record_datetime');
            $table->json('extra');
            $table->text('formatted');
            $table->string('remote_addr')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps(); // Includes `created_at` and `updated_at`
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('backend_error_logs');
    }
};
