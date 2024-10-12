<?php

declare(strict_types=1);

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Workbench\App\Models\Organization;

class OrganizationFactory extends Factory
{
    protected $model = Organization::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'domain' => $this->faker->domainName(),
            'country_code' => $this->faker->countryCode(),
            'email' => $this->faker->companyEmail(),
            'city' => $this->faker->city(),
            'status' => $this->faker->randomElement(['operating', 'closed']),
            'short_description' => $this->faker->paragraph(),
            'num_funding_rounds' => $this->faker->numberBetween(0,15),
            'total_funding_usd' => $this->faker->numberBetween(1000000,5000000),
            'founded_on' => $this->faker->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
