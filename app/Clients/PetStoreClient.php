<?php

namespace App\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class PetStoreClient
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.petstore.base_url', '/'));
        $this->apiKey  = config('services.petstore.api_key');
    }

    protected function client()
    {
        $client = Http::acceptJson();
        if ($this->apiKey) {
            // dodany zawsze
            $client = $client->withHeaders([
                'api_key' => $this->apiKey,
            ]);
        }

        return $client->baseUrl($this->baseUrl);
    }

    /**
         * GET /pet/findByStatus?status=available
         *
         * @return array lista Petów (tablice asocjacyjne)
         * @throws ConnectionException|RequestException
         */

    public function findPetsByStatus(string $status = 'available'): array
    {
        $response = $this->client()->get('/pet/findByStatus', [
            'status' => $status,
        ]);

        // rzuci wyjątek dla 4xx/5xx
        $response->throw();

        return $response->json() ?? [];
    }

    /**
     * GET /pet/{petId}
     *
     * @throws ConnectionException|RequestException
     */

    public function findPetById(int $id): array
    {
        $response = $this->client()->get("/pet/{$id}");

        $response->throw();

        return $response->json() ?? [];
    }

    public function createPet(array $petPayload)
    {
        $response = $this->client()->post('/pet/', $petPayload);

        $response->throw();

        return $response->json() ?? [];
    }
}
