<?php

  declare(strict_types=1);

  namespace UseTheFork\Synapse\Services\Clearbit\Requests;

  use Saloon\Enums\Method;
  use Saloon\Http\Request;

  class ClearbitPersonRequest extends Request
  {

    protected Method $method = Method::GET;

    public function __construct(
      public readonly string $email,
    ) {}

    public function resolveEndpoint(): string
    {
      return '/v2/combined/find';
    }

    protected function defaultQuery(): array
    {
      return [
        'email' => $this->email
      ];
    }
  }
