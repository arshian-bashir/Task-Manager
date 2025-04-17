<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->text('user_id'); 
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('priority', ['low', 'medium', 'high']);
            $table->enum('status', ['to_do', 'in_progress', 'awaiting_approval','completed'])->default('to_do');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
