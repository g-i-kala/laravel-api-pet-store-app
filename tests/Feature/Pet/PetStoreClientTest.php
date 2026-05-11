<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Clients\PetStoreClient;
use App\Exceptions\PetstoreException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PetStoreClientTest extends TestCase
{
    public function test_find_pets_by_status_returns_array_from_api(): void
    {
        Http::fake([
            'petstore.swagger.io/v2/pet/findByStatus*' => Http::response([
                ['id' => 1, 'name' => 'Doggie', 'status' => 'available'],
            ], 200),
        ]);

        $client = new PetStoreClient();

        $pets = $client->findPetsByStatus('available');

        $this->assertIsArray($pets);
        $this->assertCount(1, $pets);
        $this->assertSame('Doggie', $pets[0]['name']);
    }

    public function test_find_pet_by_id_throws_petstore_exception_on_404(): void
    {
        Http::fake([
            'petstore.swagger.io/v2/pet/99999' => Http::response(null, 404),
        ]);

        $client = new PetStoreClient();

        $this->expectException(PetstoreException::class);
        $this->expectExceptionMessage('nie został znaleziony w API'); // fragment Twojej wiadomości

        $client->findPetById(99999);
    }
}
