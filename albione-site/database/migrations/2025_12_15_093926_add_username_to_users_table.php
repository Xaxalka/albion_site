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
        $driver = DB::getDriverName();

        if (! Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username')->nullable()->after('name');
            });
        } elseif (in_array($driver, ['mysql', 'mariadb'], true)) {
            // If the column exists but is NOT NULL (MySQL may backfill empty strings),
            // make it nullable so we can safely normalize duplicates.
            DB::statement("ALTER TABLE `users` MODIFY `username` VARCHAR(255) NULL");
        }

        // Normalize existing data so the unique index can be created safely.
        // MySQL may backfill NOT NULL strings with empty values when adding columns.
        DB::table('users')->where('username', '')->update(['username' => null]);

        // If there are any duplicated non-null usernames, null them out (unique allows multiple NULLs).
        $duplicateUsernames = DB::table('users')
            ->select('username')
            ->whereNotNull('username')
            ->groupBy('username')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('username')
            ->filter(fn ($value) => $value !== '');

        if ($duplicateUsernames->isNotEmpty()) {
            DB::table('users')->whereIn('username', $duplicateUsernames->all())->update(['username' => null]);
        }

        $indexExists = false;

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            $indexExists = (bool) DB::selectOne("
                SELECT 1
                FROM information_schema.statistics
                WHERE table_schema = DATABASE()
                  AND table_name = 'users'
                  AND index_name = 'users_username_unique'
                LIMIT 1
            ");
        } elseif ($driver === 'sqlite') {
            $indexes = DB::select("PRAGMA index_list('users')");
            $indexExists = collect($indexes)->contains(fn ($row) => ($row->name ?? null) === 'users_username_unique');
        }

        if (! $indexExists) {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('username', 'users_username_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if (! Schema::hasColumn('users', 'username')) {
            return;
        }

        $indexExists = false;

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            $indexExists = (bool) DB::selectOne("
                SELECT 1
                FROM information_schema.statistics
                WHERE table_schema = DATABASE()
                  AND table_name = 'users'
                  AND index_name = 'users_username_unique'
                LIMIT 1
            ");
        } elseif ($driver === 'sqlite') {
            $indexes = DB::select("PRAGMA index_list('users')");
            $indexExists = collect($indexes)->contains(fn ($row) => ($row->name ?? null) === 'users_username_unique');
        }

        if ($indexExists) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique('users_username_unique');
            });
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};
