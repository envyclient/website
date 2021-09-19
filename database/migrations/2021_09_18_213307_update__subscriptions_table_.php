<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('billing_agreement_id');

            $table->renameColumn('stripe_status', 'status');
            $table->datetime('end_date')->nullable()->change();

            $table->string('paypal_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreignId('billing_agreement_id')->nullable()->constrained();

            $table->renameColumn('status', 'stripe_status');
            $table->datetime('end_date')->change();

            $table->dropColumn('paypal_id');
        });
    }
}
