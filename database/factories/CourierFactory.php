<?php

namespace Database\Factories;

use App\Enums\ProductTypeEnum;
use App\Models\Courier;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Courier>
 */
class CourierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Courier::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Aramex',
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'description' => fake()->sentence(10),
            'demo_url' => fake()->url(),
            'live_url' => fake()->url(),
            'credentials' => [
                'token' => Str::random(10),
                'account_pin' => Str::random(10),
                'account_number' => Str::random(10),
            ],
            'supported_product_types' =>
            fake()->randomElements(array_column(ProductTypeEnum::cases(), 'name'), 2),
            'max_shipments' => fake()->numberBetween(50, 1000)
        ];
    }
}