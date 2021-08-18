<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('transaction_id');
            $table->string('idcontrato')->unique();
            $table->string('nAnuncio')->nullable();
            $table->string('tipoContrato')->nullable();
            $table->string('tipoprocedimento')->nullable();
            $table->longText('objectoContrato')->nullable();
            $table->longText('adjudicantes')->nullable();
            $table->longText('adjudicatarios')->nullable();
            $table->date('dataPublicacao')->nullable();
            $table->date('dataCelebracaoContrato')->nullable();
            $table->double('precoContratual')->nullable();
            $table->longText('cpv')->nullable();
            $table->double('prazoExecucao')->nullable();
            $table->longText('localExecucao')->nullable();
            $table->string('fundamentacao')->nullable();
            $table->boolean('read')->default(false);
            $table->timestamp('read_at')->nullable();
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
        Schema::dropIfExists('records');
    }
}