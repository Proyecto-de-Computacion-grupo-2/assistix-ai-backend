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
        Schema::create('league_user', function (Blueprint $table) {
            $table->integer('id_user')->primary();
            $table->string('email', 255)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('team_name', 255);
            $table->integer('team_points')->nullable();
            $table->float('team_average')->nullable();
            $table->integer('team_value')->nullable();
            $table->integer('team_players')->nullable();
            $table->integer('current_balance')->nullable();
            $table->integer('future_balance')->nullable();
            $table->integer('maximum_debt')->nullable();
            $table->boolean('active')->default(true);
        });

        Schema::create('player', function (Blueprint $table) {
            $table->integer('id_mundo_deportivo')->primary();
            $table->integer('id_sofa_score')->nullable();
            $table->integer('id_marca')->nullable();
            $table->integer('id_user');
            $table->string('full_name', 255);
            $table->integer('position');
            $table->integer('player_value')->nullable();
            $table->boolean('is_in_market')->default(false);
            $table->integer('sell_price')->nullable();
            $table->string('photo_body', 255)->nullable();
            $table->string('photo_face', 255)->nullable();
            $table->integer('season_15_16')->nullable();
            $table->integer('season_16_17')->nullable();
            $table->integer('season_17_18')->nullable();
            $table->integer('season_18_19')->nullable();
            $table->integer('season_19_20')->nullable();
            $table->integer('season_20_21')->nullable();
            $table->integer('season_21_22')->nullable();
            $table->integer('season_22_23')->nullable();
            $table->integer('season_23_24')->nullable();
            $table->foreign('id_user')->references('id_user')->on('league_user')->onDelete('cascade');
        });

        Schema::create('game', function (Blueprint $table) {
            $table->id('id_game');
            $table->integer('id_gw')->nullable();
            $table->integer('id_mundo_deportivo');
            $table->string('schedule', 255)->nullable();
            $table->integer('game_week')->nullable();
            $table->string('team', 255)->nullable();
            $table->string('opposing_team', 255)->nullable();
            $table->integer('mixed')->nullable();
            $table->integer('as_score')->nullable();
            $table->integer('marca_score')->nullable();
            $table->integer('mundo_deportivo_score')->nullable();
            $table->integer('sofa_score')->nullable();
            $table->integer('current_value')->nullable();
            $table->integer('points')->nullable();
            $table->integer('average')->nullable();
            $table->integer('matches')->nullable();
            $table->integer('goals_metadata')->nullable();
            $table->integer('cards')->nullable();
            $table->integer('yellow_card')->nullable();
            $table->integer('double_yellow_card')->nullable();
            $table->integer('red_card')->nullable();
            $table->integer('total_passes')->nullable();
            $table->integer('accurate_passes')->nullable();
            $table->integer('total_long_balls')->nullable();
            $table->integer('accurate_long_balls')->nullable();
            $table->integer('total_cross')->nullable();
            $table->integer('accurate_cross')->nullable();
            $table->integer('total_clearance')->nullable();
            $table->integer('clearance_off_line')->nullable();
            $table->integer('aerial_lost')->nullable();
            $table->integer('aerial_won')->nullable();
            $table->integer('duel_lost')->nullable();
            $table->integer('duel_won')->nullable();
            $table->integer('dispossessed')->nullable();
            $table->integer('challenge_lost')->nullable();
            $table->integer('total_contest')->nullable();
            $table->integer('won_contest')->nullable();
            $table->integer('good_high_claim')->nullable();
            $table->integer('punches')->nullable();
            $table->integer('error_lead_to_a_shot')->nullable();
            $table->integer('error_lead_to_a_goal')->nullable();
            $table->integer('shot_off_target')->nullable();
            $table->integer('on_target_scoring_attempt')->nullable();
            $table->integer('hit_woodwork')->nullable();
            $table->integer('blocked_scoring_attempt')->nullable();
            $table->integer('outfielder_block')->nullable();
            $table->integer('big_chance_created')->nullable();
            $table->integer('big_chance_missed')->nullable();
            $table->integer('penalty_conceded')->nullable();
            $table->integer('penalty_won')->nullable();
            $table->integer('penalty_miss')->nullable();
            $table->integer('penalty_save')->nullable();
            $table->integer('goals')->nullable();
            $table->integer('own_goals')->nullable();
            $table->integer('saved_shots_from_inside_the_box')->nullable();
            $table->integer('saves')->nullable();
            $table->integer('goal_assist')->nullable();
            $table->integer('goals_against')->nullable();
            $table->integer('goals_avoided')->nullable();
            $table->integer('interception_won')->nullable();
            $table->integer('total_interceptions')->nullable();
            $table->integer('total_keeper_sweeper')->nullable();
            $table->integer('accurate_keeper_sweeper')->nullable();
            $table->integer('total_tackle')->nullable();
            $table->integer('was_fouled')->nullable();
            $table->integer('fouls')->nullable();
            $table->integer('total_offside')->nullable();
            $table->integer('minutes_played')->nullable();
            $table->integer('touches')->nullable();
            $table->integer('last_man_tackle')->nullable();
            $table->integer('possession_lost_control')->nullable();
            $table->integer('expected_goals')->nullable();
            $table->integer('goals_prevented')->nullable();
            $table->integer('key_pass')->nullable();
            $table->integer('expected_assists')->nullable();
            $table->dateTime('ts')->nullable();
            $table->foreign('id_mundo_deportivo')->references('id_mundo_deportivo')->on('player')->onDelete('cascade');
        });

        Schema::create('absence', function (Blueprint $table) {
            $table->id('id_absence');
            $table->integer('id_mundo_deportivo');
            $table->string('type_absence', 255)->nullable();
            $table->string('description_absence', 255)->nullable();
            $table->string('since', 255);
            $table->string('until', 255);
            $table->foreign('id_mundo_deportivo')->references('id_mundo_deportivo')->on('player')->onDelete('cascade');
        });

        Schema::create('price_variations', function (Blueprint $table) {
            $table->id('id_price_variation');
            $table->integer('id_mundo_deportivo');
            $table->date('price_day')->nullable();
            $table->integer('price')->nullable();
            $table->boolean('is_prediction')->default(false);
            $table->foreign('id_mundo_deportivo')->references('id_mundo_deportivo')->on('player')->onDelete('cascade');
        });

        Schema::create('prediction_point', function (Blueprint $table) {
            $table->id('id_prediction_points');
            $table->integer('id_mundo_deportivo');
            $table->integer('gameweek')->nullable();
            $table->date('date_prediction')->nullable();
            $table->integer('point_prediction')->nullable();
            $table->foreign('id_mundo_deportivo')->references('id_mundo_deportivo')->on('player')->onDelete('cascade');
        });

        Schema::create('user_recommendation', function (Blueprint $table) {
            $table->id('id_user_recommendation');
            $table->integer('id_user');
            $table->integer('id_mundo_deportivo');
            $table->date('recommendation_day')->nullable();
            $table->boolean('my_team_recommendation')->nullable();
            $table->boolean('market_team_recommendation')->nullable();
            $table->integer('gameweek')->nullable();
            $table->string('operation_type', 255)->nullable();
            $table->integer('expected_value_percentage')->nullable();
            $table->date('expected_value_day')->nullable();
            $table->foreign('id_mundo_deportivo')->references('id_mundo_deportivo')->on('player')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('league_user')->onDelete('cascade');
        });

        Schema::create('global_recommendation', function (Blueprint $table) {
            $table->id('id_global_recommendation');
            $table->integer('id_mundo_deportivo');
            $table->string('lineup', 255)->nullable();
            $table->integer('gameweek')->nullable();
            $table->foreign('id_mundo_deportivo')->references('id_mundo_deportivo')->on('player')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('league_user');
        Schema::dropIfExists('player');
        Schema::dropIfExists('game');
        Schema::dropIfExists('absence');
        Schema::dropIfExists('price_variation');
        Schema::dropIfExists('prediction_point');
        Schema::dropIfExists('user_recommendation');
        Schema::dropIfExists('global_recommendation');
    }
};
