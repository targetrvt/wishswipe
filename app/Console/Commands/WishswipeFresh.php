<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class WishswipeFresh extends Command
{
    protected $signature = 'wishswipe:fresh';

    /**
     * The console command description.
     */
    protected $description = 'Drop all tables (destructive), run migrations, and then run db:seed.';

    public function handle()
    {
        // Confirmation unless --no-interaction passed globally
        if (! $this->option('no-interaction')) {
            $confirm = $this->confirm(
                '⚠️  This will DROP ALL TABLES in the configured database. Do you want to continue?',
                true // <-- default is now "Yes"
            );
            if (! $confirm) {
                $this->info('Aborted. No changes made.');
                return 1;
            }
        }

        $connection = config('database.default');
        $driver = config("database.connections.$connection.driver");

        try {
            switch ($driver) {
                case 'mysql':
                    DB::statement('SET FOREIGN_KEY_CHECKS=0');
                    $rows = DB::select('SHOW FULL TABLES WHERE Table_type = ?', ['BASE TABLE']);
                    foreach ($rows as $row) {
                        $rowArr = (array) $row;
                        $table = array_values($rowArr)[0];
                        DB::statement("DROP TABLE IF EXISTS `$table`");
                    }
                    DB::statement('SET FOREIGN_KEY_CHECKS=1');
                    break;

                case 'pgsql':
                    DB::statement('DROP SCHEMA public CASCADE');
                    DB::statement('CREATE SCHEMA public');
                    break;

                case 'sqlite':
                    $database = config("database.connections.$connection.database");
                    $path = database_path($database);
                    if (file_exists($path)) {
                        @unlink($path);
                    }
                    touch($path);
                    break;

                default:
                    $this->error("Unsupported database driver: $driver");
                    return 1;
            }

            $this->info('All tables (or schema) dropped successfully.');

            $this->info('Running migrations...');
            $this->call('migrate', ['--force' => true]);

            // Vienmēr palaiž seederus
            $this->info('Running db:seed...');
            $this->call('db:seed', ['--force' => true]);

            $this->info('Migrations and seeders finished.');
            return 0;
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            return 1;
        }
    }
}
