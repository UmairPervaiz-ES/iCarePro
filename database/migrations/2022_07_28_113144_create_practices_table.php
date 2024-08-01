<?php

use App\Models\Practice\Practice;
use App\Models\Practice\PracticePaymentMethod;
use App\Models\Subscription\SubscriptionPermission;
use App\Models\Subscription\SubscriptionTransaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Contracts\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('practices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_registration_request_id')->references('id')->on('practice_registration_requests')->onDelete('cascade');
            $table->foreignId('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
            $table->boolean('auto_renew')->default('0');
            $table->string('practice_key' , 20)->unique();
            $table->string('logo_url')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->boolean('is_password_reset')->default('0');
            $table->string('tax_id' , 40);
            $table->string('practice_npi' , 15); //
            $table->string('practice_taxonomy' , 40);
            $table->string('facility_id' , 30);
            $table->string('oid' , 30 );
            $table->string('clia_number' , 35);
            $table->longText('privacy_policy');
            $table->boolean('terms_and_condition')->default('1');
            $table->enum('status', ['Pending', 'Inreview','Accepted','Rejected'])->default('Pending');
            $table->string('created_by' , 20)->nullable();
            $table->string('updated_by' , 20)->nullable();
            $table->string('customer_id', 50)->nullable();
            $table->timestamp('subscription_expiry_date')->nullable();
            $table->integer('renew_tries_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->rememberToken();
            $table->index(['practice_key']);

        }); 
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('practices');
    }
};
