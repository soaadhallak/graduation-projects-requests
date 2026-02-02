<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ProjectRequest;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('team_join_requests', function (Blueprint $table) {
            $table->foreignIdFor(ProjectRequest::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_join_requests', function (Blueprint $table) {
            $table->dropForeignIdFor(ProjectRequest::class);
            $table->dropColumn('project_request_id');
        });
    }
};
