<?php

use App\Models\Major;
use App\Models\Team;
use App\Models\User;
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
        Schema::create('students', function (Blueprint $table) {
            $table->string('university_number');
            $table->text('skills');
            $table->foreignIdFor(User::class)->primary()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Major::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Team::class)->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
