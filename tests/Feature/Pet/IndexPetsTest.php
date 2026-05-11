<?php

declare(strict_types=1);

namespace Tests\Feature\Pet;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IndexPetsTest extends TestCase
{
    public function test_it_displays_pets_list_from_api(): void
    {
        Http::fake([
            'petstore.swagger.io/v2/pet/findByStatus*' => Http::response([
                [
                    'id' => 1,
                    'category' => ['id' => 1, 'name' => 'Dogs'],
                    'name' => 'Doggie',
                    'photoUrls' => ['https://example.com/dog.jpg'],
                    'tags' => [
                        ['id' => 1, 'name' => 'friendly'],
                    ],
                    'status' => 'available',
                ],
            ], 200),
        ]);

        $response = $this->get('/pets');

        $response->assertStatus(200);
        $response->assertSee('Doggie');
        $response->assertSee('available');
    }
}
