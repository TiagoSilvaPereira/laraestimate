<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('description');
            $table->string('duration')->nullable();
            $table->decimal('price')->nullable()->default(0);
            $table->boolean('obligatory')->default(false);

            $table->smallInteger('position')->unsigned()->default(0);

            $table->char('section_id', 36);
            $table->foreign('section_id')
                ->references('id')
                ->on('sections')
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
        Schema::dropIfExists('items');
    }
}
