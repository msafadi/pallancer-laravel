<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // id: BigInt, Unsigned, auto increment, primary key
            $table->string('name')->unique();
            $table->unsignedBigInteger('parent_id')
            ->nullable();
            $table->enum('status', ['published', 'draft'])->default('published');
            $table->timestamps(); // created_at + updated_at: TIMESTAMP (DATETIME)
            //$table->timestamp('created_on');
            //$table->timestamp('updated_on');
        
            $table->foreign('parent_id')
                ->references('id')
                ->on('categories');
                //->onDelete('null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}

