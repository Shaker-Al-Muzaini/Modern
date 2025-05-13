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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('type',40);
            $table->string('address1',200);
            $table->string('address2',200)->nullable();
            $table->string('city',100);
            $table->string('state',100)->nullable();
            $table->string('zipcode',40);
            $table->boolean('isMain')->default(1);
            $table->string('country_code',10);
            $table->foreignId('user_id')
                ->references('id')
                ->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
