<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CompareDatabases extends Command
{
    protected $signature = 'db:compare';
    protected $description = 'Compare local and remote MySQL databases and show differences';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            // Print the current environment
            $this->info("Environment: " . env('APP_ENV'));
            $this->info("Loaded .env file: " . base_path('.env'));

            // Connect to local and remote databases
            $localDB = DB::connection('mysql');
            $remoteDB = DB::connection('mysql_second');

            // Fetch table names from both databases
            $localTables = $this->getTableNames($localDB, env('DB_DATABASE'));
            $this->info('Tables in local database:');
            $this->table(['Table Name'], array_map(fn($table) => [$table], $localTables));

            $remoteTables = $this->getTableNames($remoteDB, env('DB_SECOND_DATABASE'));
            $this->info('Tables in remote database:');
            $this->table(['Table Name'], array_map(fn($table) => [$table], $remoteTables));

            // Compare tables and columns
            $this->compareTables($localDB, $remoteDB, $localTables, $remoteTables);
        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
        }
    }

    private function getTableNames($dbConnection, $databaseName)
    {
        $tables = $dbConnection->select('SHOW TABLES');
        $tableNameKey = 'Tables_in_' . strtolower($databaseName); // Ensure case-insensitivity
        return array_map(fn($table) => strtolower($table->$tableNameKey), $tables);
    }

    private function compareTables($localDB, $remoteDB, $localTables, $remoteTables)
    {
        // Compare table lists
        $this->compareTableLists($localTables, $remoteTables);

        // Compare columns for common tables
        $commonTables = array_intersect($localTables, $remoteTables);
        $differences = [];

        foreach ($commonTables as $table) {
            $localColumns = $this->getTableColumns($localDB, $table);
            $remoteColumns = $this->getTableColumns($remoteDB, $table);

            $missingInRemote = array_diff($localColumns, $remoteColumns);
            $missingInLocal = array_diff($remoteColumns, $localColumns);

            if ($missingInRemote || $missingInLocal) {
                $differences[$table] = [
                    'missing_in_remote' => $missingInRemote,
                    'missing_in_local' => $missingInLocal,
                ];
            }
        }

        // Display column differences
        if (!empty($differences)) {
            foreach ($differences as $table => $diff) {
                $this->info("Differences in table: $table");

                if (!empty($diff['missing_in_remote'])) {
                    $this->warn("  Columns missing in remote:");
                    foreach ($diff['missing_in_remote'] as $column) {
                        $this->line("    - $column");
                    }
                }

                if (!empty($diff['missing_in_local'])) {
                    $this->warn("  Columns missing in local:");
                    foreach ($diff['missing_in_local'] as $column) {
                        $this->line("    - $column");
                    }
                }
            }
        } else {
            $this->info("All columns match in the common tables.");
        }
    }

    private function compareTableLists($localTables, $remoteTables)
    {
        $missingInRemote = array_diff($localTables, $remoteTables);
        $missingInLocal = array_diff($remoteTables, $localTables);

        if (!empty($missingInRemote)) {
            $this->warn("Tables missing in remote database:");
            $this->table(['Table Name'], array_map(fn($table) => [$table], $missingInRemote));
        }

        if (!empty($missingInLocal)) {
            $this->warn("Tables missing in local database:");
            $this->table(['Table Name'], array_map(fn($table) => [$table], $missingInLocal));
        }

        if (empty($missingInRemote) && empty($missingInLocal)) {
            $this->info("Both databases have the same tables.");
        }
    }

    private function getTableColumns($dbConnection, $table)
    {
        $columns = $dbConnection->select("SHOW COLUMNS FROM `$table`");
        return array_map(fn($column) => strtolower($column->Field), $columns); // Ensure case-insensitivity
    }
}
