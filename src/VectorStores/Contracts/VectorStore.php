<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\VectorStores\Contracts;

interface VectorStore
{
    /**
     * Executes when a search is performed on the store.
     *
     * @param  string|null  $input  The text query, or None if not using text-based query.
     * @param  array|null  $inputVector  The input vector array.
     * @param  int  $k  The total number of results to retrieve.
     * @param  string|null  $vectorQueryField  The field containing the vector representations in the index.
     * @param  string|null  $textField  The field containing the text data in the index.
     * @param  array|null  $filter  List of filter clauses to apply to the query.
     * @return array The array response.
     */
    public function handle(?string $input, ?array $inputVector, int $k, ?string $vectorQueryField, ?string $textField, ?array $filter): array;

    /**
     * Executes when the index is created.
     *
     * @param  string|null  $input  The text query, or None if not using text-based query.
     * @param  array|null  $inputVector  The input vector array.
     * @param  int  $k  The total number of results to retrieve.
     * @param  string|null  $vectorQueryField  The field containing the vector representations in the index.
     * @param  string|null  $textField  The field containing the text data in the index.
     * @param  array|null  $filter  List of filter clauses to apply to the query.
     * @return array The array response.
     */
    public function index(?string $input, ?array $inputVector, int $k, ?string $vectorQueryField, ?string $textField, ?array $filter): array;
}
