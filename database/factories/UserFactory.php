<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    private static array $nepaliNames = [
        'Aarav', 'Sita', 'Rohan', 'Priya', 'Bibek',
        'Anita', 'Sagar', 'Roshani', 'Nabin', 'Kabita',
    ];

    public function definition(): array
    {
        $name = fake()->unique()->randomElement(self::$nepaliNames);

        return [
            'name' => $name,
            'email' => strtolower(str_replace(' ', '.', $name)) . '@gmail.com',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
