<?php

declare(strict_types=1);

namespace Tests\Feature\Pet;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IndexPetsErrorTest extends TestCase
{
    public function test_it_shows_error_message_when_api_fails_on_index(): void
    {
        Http::fake([
            'petstore.swagger.io/v2/pet/findByStatus*' => Http::response(null, 500),
        ]);

        $response = $this->get('/pets');

        $response->assertRedirect();

        $this->assertStringContainsString(
            'Błąd podczas pobierania listy',
            session('error')
        );

    }
}
