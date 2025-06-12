<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_ip_address_to_login_histories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIpAddressToLoginHistoriesTable extends Migration
{
    public function up()
    {
        Schema::table('login_histories', function (Blueprint $table) {
            $table->string('ip_address')->nullable(); // Menambahkan kolom ip_address
        });
    }

    public function down()
    {
        Schema::table('login_histories', function (Blueprint $table) {
            $table->dropColumn('ip_address'); // Menghapus kolom ip_address saat rollback
        });
    }
}
