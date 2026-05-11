<?php

namespace App\Clients;

use App\Exceptions\PetstoreException;
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
        try {
            $response = $this->client()->get('/pet/findByStatus', [
                'status' => $status,
            ]);
            $response->throw();
        } catch (ConnectionException $e) {
            throw new PetstoreException('Brak odpowiedzi z API Petstore podczas pobierania listy zwierzaków.', null);
        } catch (RequestException $e) {
            throw new PetstoreException('Błąd podczas pobierania listy zwierzaków z API.', $e->response);
        }

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
        try {
            $response = $this->client()->get("/pet/{$id}");
            $response->throw();
        } catch (ConnectionException $e) {
            throw new PetstoreException('Brak odpowiedzi z API Petstore podczas pobierania szczegółów zwierzaka.', null);
        } catch (RequestException $e) {
            $status = $e->response->status();

            if ($status === 404) {
                throw new PetstoreException("Zwierzak o ID {$id} nie został znaleziony w API.", $e->response);
            }

            throw new PetstoreException('Błąd podczas pobierania szczegółów zwierzaka z API.', $e->response);
        }

        return $response->json() ?? [];
    }

    public function createPet(array $petPayload)
    {
        try {
            $response = $this->client()->post('/pet', $petPayload);
            $response->throw();
        } catch (ConnectionException $e) {
            throw new PetstoreException('Nie udało się połączyć z API Petstore podczas tworzenia zwierzaka.', null);
        } catch (RequestException $e) {
            throw new PetstoreException('API Petstore zwróciło błąd podczas tworzenia zwierzaka.', $e->response);
        }

        return $response->json() ?? [];
    }

    public function updatePet(array $petPayload)
    {
        try {
            $response = $this->client()->put('/pet', $petPayload);
            $response->throw();
        } catch (ConnectionException $e) {
            throw new PetstoreException('Nie udało się połączyć z API Petstore podczas aktualizacji danych zwierzaka.', null);
        } catch (RequestException $e) {
            throw new PetstoreException('API Petstore zwróciło błąd podczas aktualizacji danych zwierzaka.', $e->response);
        }

        $response->throw();

        return $response->json() ?? [];
    }

    public function deletePet(int $id)
    {
        try {
            $response = $this->client()->delete("/pet/{$id}");
            $response->throw();
        } catch (ConnectionException $e) {
            throw new PetstoreException('Nie udało się połączyć z API Petstore podczas usuwania zwierzaka.', null);
        } catch (RequestException $e) {
            $status = $e->response->status();

            if ($status === 404) {
                throw new PetstoreException("Zwierzak o ID {$id} nie został znaleziony w API (nie można usunąć).", $e->response);
            }

            throw new PetstoreException('API Petstore zwróciło błąd podczas usuwania zwierzaka.', $e->response);
        }

        return $response->status();
    }
}
