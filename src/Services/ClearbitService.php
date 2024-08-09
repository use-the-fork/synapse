<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse\Services;


    use Illuminate\Support\Facades\Http;

    class ClearbitService
    {
        public function __construct(
            private readonly string $apiKey
        ) {
        }

        public function searchPerson(string $email)
        {

            $response = Http::withHeaders([
                                              'Authorization' => 'Bearer ' . $this->apiKey,
                                              'Content-Type'  => 'application/json',
                                          ])->get('https://person-stream.clearbit.com/v2/combined/find?email=' . $email)->json();

            return $response;
        }

        public function searchCompany(string $domain)
        {

            $response = Http::withHeaders([
                                              'Authorization' => 'Bearer ' . $this->apiKey,
                                              'Content-Type'  => 'application/json',
                                          ])
                            ->timeout(90)
                            ->get('https://company.clearbit.com/v2/companies/find?domain=' . $domain)->json();

            return $response;
        }
    }
