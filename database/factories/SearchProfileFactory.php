<?php

namespace Database\Factories;

use App\Models\SearchProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class SearchProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SearchProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid(),
            'name' => $this->faker->name(),
            'propertyType' => $this->faker->uuid(),
            'searchFields' => collect()
        ];
    }
}
