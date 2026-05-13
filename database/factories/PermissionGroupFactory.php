<?php

namespace WebFresh\UserManager\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use WebFresh\UserManager\Models\PermissionGroup;

class PermissionGroupFactory extends Factory
{
    protected $model = PermissionGroup::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
