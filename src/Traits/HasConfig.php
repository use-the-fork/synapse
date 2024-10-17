<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse\Traits;

    use UseTheFork\Synapse\Contracts\ArrayStore as ArrayStoreContract;
    use UseTheFork\Synapse\Repositories\ArrayStore;

    trait HasConfig
    {
        /**
         * Request Config
         */
        protected ArrayStoreContract $config;

        /**
         * Access the config
         */
        public function config(): ArrayStoreContract
        {
            return $this->config ??= new ArrayStore($this->defaultConfig());
        }

        /**
         * Default Config
         *
         * @return array<string, mixed>
         */
        protected function defaultConfig(): array
        {
            return [];
        }
    }
