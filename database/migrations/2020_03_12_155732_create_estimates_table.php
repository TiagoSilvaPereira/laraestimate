<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstimatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimates', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->boolean('use_name_as_title')->default(true);
            $table->date('expiration_date')->nullable();

            $table->string('currency_symbol')->nullable()->default('$');
            $table->string('currency_decimal_separator')->nullable()->default('.');
            $table->string('currency_thousands_separator')->nullable()->default('');

            $table->boolean('allows_to_select_items')->default(true);
            $table->string('password')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estimates');
    }
}
