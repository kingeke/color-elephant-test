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

// idcontrato
// nAnuncio
// tipoContrato
// tipoprocedimento
// objectoContrato
// adjudicantes
// adjudicatarios
// dataPublicacao
// dataCelebracaoContrato
// precoContratual
// cpv
// prazoExecucao
// localExecucao
// fundamentacao

