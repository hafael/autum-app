<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('spend_limits', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->integer('price')->default(0);
            $table->integer('requests')->default(0);
            $table->integer('objects')->default(0);
            $table->integer('teams')->default(0);
            $table->integer('team_members')->default(0);
            $table->integer('trial_period_days')->default(0);
        });
        
        DB::statement("
            CREATE OR REPLACE VIEW current_spend_user AS
            SELECT 	us.id as user_id,
                    us.spend_limit_code as code,
                    (SELECT count(*) from teams rq WHERE us.id = rq.user_id) AS teams_count,
                    COALESCE((SELECT teams from spend_limits sl WHERE us.spend_limit_code = sl.code), 0) AS max_teams,
                    COALESCE((SELECT team_members from spend_limits sl WHERE us.spend_limit_code = sl.code), 0) AS max_team_members,
                    COALESCE((SELECT trial_period_days from spend_limits sl WHERE us.spend_limit_code = sl.code), 0) AS trial_period_days
            FROM users us
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spend_limits');
        
        DB::statement('
            DROP VIEW IF EXISTS current_spend_user
        ');
    }
};
