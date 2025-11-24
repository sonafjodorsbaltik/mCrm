<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => function () {
                $phoneUtil = PhoneNumberUtil::getInstance();
                $allRegions = $phoneUtil->getSupportedRegions();
                $region = fake()->randomElement($allRegions);
                $exampleNumber = $phoneUtil->getExampleNumber($region);
                return $phoneUtil->format($exampleNumber, PhoneNumberFormat::E164);
            },
            'email' => fake()->unique()->safeEmail()
        ];
    }
}
