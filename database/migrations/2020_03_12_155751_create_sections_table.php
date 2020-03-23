<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->text('text')->nullable();
            $table->enum('type', ['text', 'contact', 'prices']);

            $table->smallInteger('position')->unsigned()->default(0);

            $table->char('estimate_id', 36);
            $table->foreign('estimate_id')
                ->references('id')
                ->on('estimates')
                ->onDelete('CASCADE');

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
        Schema::dropIfExists('sections');
    }
}
