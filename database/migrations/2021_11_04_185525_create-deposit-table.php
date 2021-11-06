<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposit', function (Blueprint $table) {

            $table->increments("id");
            $table->string("description");
            $table->decimal("value", 15, 2);
            $table->timestamps();
            $table->integer("fk_user");
            $table->string("check_img");
            $table->integer("fk_deposit_status");
            $table->foreign("fk_deposit_status")->references("id")->on("deposit_status")->onDelete("cascade");
            $table->foreign("fk_user")->references("id")->on("user")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deposit');
    }
}
