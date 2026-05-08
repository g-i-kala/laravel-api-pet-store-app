<?php

namespace Tests\Feature\Pet;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ShowPetNotFoundTest extends TestCase
{
    public function test_it_redirects_with_error_when_pet_not_found(): void
    {
        Http::fake([
            'petstore.swagger.io/v2/pet/12345' => Http::response(null, 404),
        ]);

        $response = $this->get('/pets/12345');

        $response->assertRedirect(); // powinno lecieć na /pets?status=available

        $this->followRedirects($response)
            ->assertSee('Zwierzak o ID 12345 nie został znaleziony w API'); // dopasuj do swojego message
    }
}
