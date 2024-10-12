<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse\Tools\SQL;

    use Illuminate\Database\QueryException;
    use Illuminate\Support\Facades\DB;
    use UseTheFork\Synapse\Tools\BaseTool;

    final class QuerySQLDataBaseTool extends BaseTool
    {

        /**
         * Execute a SQL SELECT query against the database and get back the result. If the query is not correct, an error message will be returned. If an error is returned, rewrite the query, check the query, and try again.
         *
         * @param  string  $query  A detailed and correct SQL SELECT query.
         */
        public function handle(
            string $query,
        ): string {
            try {
                $query = DB::select($query);
            } catch (QueryException $e) {
                return $e->getMessage();
            }

            return json_encode($query);
        }
    }
