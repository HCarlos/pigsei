<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('nombre')->nullable();
            $table->string('ap_paterno')->nullable();
            $table->string('ap_materno')->nullable();
            $table->string('curp',18)->default('')->nullable();
            $table->string('emails',500)->default('')->nullable();
            $table->string('celulares',250)->default('')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->smallInteger('genero')->default(0)->nullable();
            $table->string('root',150)->default('')->nullable();
            $table->string('filename',50)->nullable();
            $table->string('filename_png',50)->nullable();
            $table->string('filename_thumb',50)->nullable();
            $table->string('session_id')->nullable();
            $table->unsignedSmallInteger('status_user')->default(1)->nullable();
            $table->unsignedSmallInteger('empresa_id')->default(0)->nullable();
            $table->string('ip',150)->default('')->nullable();
            $table->string('host',150)->default('')->nullable();
            $table->index('empresa_id');
            $table->boolean('logged')->default(false)->index();
            $table->timestamp('logged_at')->nullable()->index();
            $table->timestamp('logout_at')->nullable()->index();
            $table->timestamp('email_verified_at')->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });



        DB::statement("ALTER DATABASE dbpigsei set default_text_search_config = 'spanish'");
        DB::statement("ALTER TABLE users ADD COLUMN searchtext TSVECTOR");
        DB::statement("UPDATE users SET searchtext = to_tsvector('spanish', coalesce(trim(ap_paterno),'') || ' ' || coalesce(trim(ap_paterno),'') || ' ' || coalesce(trim(nombre),'') || ' ' || coalesce(trim(curp),'') || ' ' || coalesce(trim(username),'') || ' ' || coalesce(trim(email),'') )");
        DB::statement("CREATE INDEX user_searchtext_gin ON users USING GIN(searchtext)");
        DB::statement("CREATE TRIGGER ts_searchtext_user1 BEFORE INSERT OR UPDATE ON users FOR EACH ROW EXECUTE PROCEDURE tsvector_update_trigger('searchtext', 'pg_catalog.spanish', 'ap_paterno', 'ap_materno', 'nombre', 'curp', 'username', 'email')");


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');

    }
};
