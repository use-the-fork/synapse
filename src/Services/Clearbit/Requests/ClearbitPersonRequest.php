<?php

  declare(strict_types=1);

  namespace UseTheFork\Synapse\Services\Clearbit\Requests;

  use Saloon\Enums\Method;
  use Saloon\Http\Request;

  class ClearbitPersonRequest extends Request
  {

    protected Method $method = Method::GET;

      /**
       * Constructor for the class.
       *
       * @param string $email The email address to lookup.
       */
      public function __construct(
      public readonly string $email,
    ) {}

      /**
       * @inheritdoc
       *
       */
    public function resolveEndpoint(): string
    {
      return '/v2/combined/find';
    }

      /**
       * @inheritdoc
       *
       */
    protected function defaultQuery(): array
    {
      return [
        'email' => $this->email
      ];
    }
  }
