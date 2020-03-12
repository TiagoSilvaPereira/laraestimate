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
            $table->id();

            $table->string('title');
            $table->text('text')->nullable();
            $table->enum('type', ['text', 'contact', 'prices']);

            $table->decimal('total')->default(0);

            $table->unsignedBigInteger('estimate_id');
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
