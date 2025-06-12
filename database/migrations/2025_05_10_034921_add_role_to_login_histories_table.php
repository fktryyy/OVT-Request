<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_role_to_login_histories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToLoginHistoriesTable extends Migration
{
    public function up()
    {
        Schema::table('login_histories', function (Blueprint $table) {
            $table->string('role')->nullable(); // Menambahkan kolom role
        });
    }

    public function down()
    {
        Schema::table('login_histories', function (Blueprint $table) {
            $table->dropColumn('role'); // Menghapus kolom role jika rollback
        });
    }
}

