<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->ipAddress('ip')->nullable();
            $table->string('device', 10)->nullable();
            $table->string('token', 50);
            $table->text('user_agent')->nullable();
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users'); 
            $lifetime = config('session.lifetime');
            $expires_at = DB::raw("NOW() + INTERVAL '{$lifetime} MINUTES'");
            $table->timestamp('expires_at')->default($expires_at);
            $table->boolean('is_remembered')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('access_tokens');
    }
}
