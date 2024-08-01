<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('drugs', function (Blueprint $table) {
            $table->id();
            $table->string('name' , 150);
            $table->enum('type' , 
            [  
                'Liquid',
                'Tablet',
                'Capsule',
                'Plasma/Topical/Serum',
                'Suppositories',
                'Drops',
                'Inhalers',
                'Injections',
                'Implants and patches',
                'Lozenges',
            ]);
            $table->enum('unit' , 
            [  
                ' ',
                'mg',
                'ml',
                'mcg',
                'mg/ml',
                'gm',
                'IU/ml',
                'ml/L',
            ]);
        
            $table->enum('intake' , 
            [  
                "Oral",
                "Inhalation",
                "Injection",
                "Topical",
                "Spray",
            ]);
            $table->foreignId('manufacture_id')->references('id')->on('manufactures')->onDelete('cascade');
            $table->string('salt_name' , 150)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drugs');
    }
};
