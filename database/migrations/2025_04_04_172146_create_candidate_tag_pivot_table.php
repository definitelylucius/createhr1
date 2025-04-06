<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('candidate_tag', function (Blueprint $table) {
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->foreignId('candidate_tag_id')->constrained()->onDelete('cascade');
            $table->primary(['candidate_id', 'candidate_tag_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('candidate_tag');
    }
};
