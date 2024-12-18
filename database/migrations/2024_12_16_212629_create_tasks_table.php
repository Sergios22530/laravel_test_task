<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable(false)
                ->comment('Task User')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('title')
                ->nullable(false)
                ->comment('Task Title');

            $table->text('description')
                ->nullable()
                ->default(null)
                ->comment('Task Description');

            $table->enum('status', ['pending', 'in_progress', 'completed'])
                ->nullable(false)
                ->comment('Task status');

            $table->enum('priority', ['low', 'medium', 'high'])
                ->nullable(false)
                ->comment('Task priority');

            $table->dateTime('completed_at')
                ->nullable()
                ->default(null)
                ->comment('Task completion date');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');                      // For filtering by status
            $table->index('completed_at');                // For sorting by completed_at
            $table->index('created_at');                  // For sorting by created_at
            $table->index(['status', 'completed_at']);    // Composite for filtering and sorting
            $table->index(['status', 'created_at']);      // Composite for filtering and sorting
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
