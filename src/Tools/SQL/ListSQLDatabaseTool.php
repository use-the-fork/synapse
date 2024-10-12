<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse\Tools\SQL;

    use Illuminate\Support\Facades\Schema;
    use UseTheFork\Synapse\Tools\BaseTool;

    final class ListSQLDatabaseTool extends BaseTool
    {
        /**
         * Tool for getting tables names. Output is a comma-separated list of tables in the database.
         *
         */
        public function handle(): string {
            $tables = collect(Schema::getTables());
            return $tables->map(function ($table) { return $table['name']; })->implode(', ');
        }
    }
