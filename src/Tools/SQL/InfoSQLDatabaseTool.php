<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse\Tools\SQL;

    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;
    use UseTheFork\Synapse\Tools\BaseTool;

    final class InfoSQLDatabaseTool extends BaseTool
    {

        /**
         * Get the schema and sample rows for the specified SQL tables.
         *
         * @param  string  $tables  A comma seperated list of tables to get information about.
         */
        public function handle(
            string $tables,
        ): string {

           $tableDescriptions = collect();
           str($tables)->explode(',')->each(function ($table) use (&$tableDescriptions) {
               $columns = json_encode(Schema::getColumns($table));
               $sample = json_encode(DB::select("SELECT * FROM {$table} LIMIT 3"));
               $tableDescriptions->push("### {$table}\n\n```json\n{$columns}\n\n```\n\n```json\n{$sample}\n\n```\n\n");

           });
           
            return "## Schema\n" . $tableDescriptions->implode("\n\n");
        }
    }
