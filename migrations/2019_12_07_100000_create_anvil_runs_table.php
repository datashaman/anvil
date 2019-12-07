<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnvilRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->create('anvil_runs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->string('command');
            $table->json('input');
            $table->text('output')->nullable();
            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();
            $table->integer('exit_code')->nullable();

            $table->unique('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->dropIfExists('anvil_runs');
    }
}
