<?php

namespace MedianetDev\LaravelAuthApi\Tests\Feature;

use MedianetDev\LaravelAuthApi\Tests\TestCase;

class RegistrationTest extends TestCase
{
    /** @test */
    public function try_to_successfully_register()
    {
        $response = $this->postJson('api/v1/register', [
            'name' => 'test -'.$this->faker->firstName,
            'email' => $this->faker->safeEmail,
            'password' => 'password',
            'c_password' => 'password',
        ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function try_to_successfully_register_using_social()
    {
        $response = $this->postJson('api/v1/login/social', [
            'name' => 'test -'.$this->faker->firstName,
            'provider_name' => 'facebook',
            'provider_id' => $this->faker->randomNumber(),
            'email' => $this->faker->safeEmail,
        ]);

        $response->assertStatus(201);
    }
}
