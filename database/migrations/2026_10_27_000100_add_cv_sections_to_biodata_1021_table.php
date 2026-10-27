<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('Biodata_1021', function (Blueprint $table) {
            $table->string('professional_title')->nullable();
            $table->text('summary')->nullable();
            $table->longText('experience')->nullable();
            $table->longText('education')->nullable();
            $table->text('skills')->nullable();
            $table->text('interests')->nullable();
            $table->text('awards')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('Biodata_1021', function (Blueprint $table) {
            $table->dropColumn([
                'professional_title',
                'summary',
                'experience',
                'education',
                'skills',
                'interests',
                'awards',
            ]);
        });
    }
};
