<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable constraints for SQLite to allow the update
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA ignore_check_constraints = ON;');
        }

        DB::table('fiche_intervention_templates')
            ->where('fiche_material_grouping', 'all_materials')
            ->update(['fiche_material_grouping' => 'per_site']);

        DB::table('fiche_intervention_templates')
            ->where('fiche_material_grouping', 'per_material_type')
            ->update(['fiche_material_grouping' => 'per_material']);

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA ignore_check_constraints = OFF;');
            return;
        }

        DB::statement("
            ALTER TABLE fiche_intervention_templates
            MODIFY fiche_material_grouping
            ENUM('per_site', 'per_material', 'per_user', 'per_marche') NOT NULL
            DEFAULT 'per_site'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('fiche_intervention_templates')
            ->whereIn('fiche_material_grouping', ['per_site', 'per_marche', 'per_user'])
            ->update(['fiche_material_grouping' => 'all_materials']);

        DB::table('fiche_intervention_templates')
            ->where('fiche_material_grouping', 'per_material')
            ->update(['fiche_material_grouping' => 'per_material_type']);

        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("
            ALTER TABLE fiche_intervention_templates
            MODIFY fiche_material_grouping
            ENUM('all_materials', 'per_material_type') NOT NULL
            DEFAULT 'all_materials'
        ");
    }
};
