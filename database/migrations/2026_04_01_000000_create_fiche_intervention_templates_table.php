<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fiche_intervention_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();

            // We use a raw statement for the ENUM if you want to match your
            // modify migration's style, or standard Blueprint.
            $table->enum('fiche_material_grouping', [
                'all_materials',
                'per_material_type'
            ])->default('all_materials');

            $table->timestamps();
        });

        // Optional: Seed some dummy data to test the update logic
        DB::table('fiche_intervention_templates')->insert([
            ['name' => 'Template A', 'fiche_material_grouping' => 'all_materials'],
            ['name' => 'Template B', 'fiche_material_grouping' => 'per_material_type'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fiche_intervention_templates');
    }
};
