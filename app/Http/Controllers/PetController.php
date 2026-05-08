<?php

namespace App\Http\Controllers;

use App\Clients\PetStoreClient;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;

class PetController extends Controller
{
    // rejestracja klienta
    public function __construct(
        protected PetStoreClient $petstoreClient
    ) {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'available');

        try {
            $pets = $this->petstoreClient->findPetsByStatus($status);
        } catch (ConnectionException $e) {
            // brak odpowiedzi API
            return back()->with('error', 'Brak odpowiedzi z API Petstore. Spróbuj ponownie później.');
        } catch (RequestException $e) {
            // błąd HTTP (4xx/5xx)
            return back()->with('error', 'Błąd podczas pobierania listy petów z API.');
        }

        return view('pets.index', [
            'pets' => $pets,
            'status' => $status,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePetRequest $request)
    {
        $validated = $request->validated();

        $photoUrls = [];
        if (!empty($validated['photo_urls'])) {
            $pattern = '/[\r\n,;]+/';  // Add more separators as needed
            $photoUrls = collect(preg_split($pattern, $validated['photo_urls']))
                ->map(fn ($line) => trim($line))
                ->filter()
                ->values()
                ->all();
        }

        $tags = [];
        if (!empty($validated['tags'])) {
            $tags = collect(explode(',', $validated['tags']))
                ->map(fn ($tag) => trim($tag))
                ->filter()
                ->map(fn ($name) => ['name' => $name])
                ->values()
                ->all();
        }

        $category = [];
        if (!empty($validated['category_name'])) {
            $category = [
                'id' => random_int(1, 100), // nie mamy id, a api chce int
                'name' => $validated['category_name'],
            ];
        }

        $petPayload = [
            'category' => $category,
            'name' => $validated['name'],
            'photoUrls' => $photoUrls,
            'tags' => $tags,
            'status' => $validated['status'],
        ];

        // wysłanie do API POST
        try {
            $createdPet = $this->petstoreClient->createPet($petPayload);
        } catch (ConnectionException) {
            return back()
                ->withInput()
                ->with('error', 'Nie udało się połączyć z API Petstore podczas tworzenia zwierzaka.');
        } catch (RequestException $e) {
            dd($e);
            return back()
                ->withInput()
                ->with('error', 'API Petstore zwróciło błąd podczas tworzenia zwierzaka.');
        }

        return redirect()
            ->route('pets.show', $createdPet['id'])
            ->with('success', "Zwierzak dodany.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // pobranie zwierzaka o id
        try {
            $pet = $this->petstoreClient->findPetById($id);
        } catch (ConnectionException $e) {
            return redirect()
                ->route('pets.index')
                ->with('error', 'Brak odpowiedzi z API Petstore podczas pobierania szczegółów.');
        } catch (RequestException $e) {

            return redirect()
                ->route('pets.index')
                ->with('error', 'Błąd podczas pobierania szczegółów listy petów z API.');
        }

        return view('pets.show', [
            'pet' => $pet,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $pet = $this->petstoreClient->findPetById($id);
        } catch (ConnectionException $e) {
            return redirect()
                ->route('pets.index')
                ->with('error', 'Brak odpowiedzi z API Petstore podczas pobierania szczegółów.');
        } catch (RequestException $e) {

            return redirect()
                ->route('pets.index')
                ->with('error', 'Błąd podczas pobierania szczegółów listy petów z API.');
        }

        $pet['tags_string'] = $this->implodeField($pet, 'tags', 'name');
        $pet['photoUrls_string'] = $this->implodeSimple($pet['photoUrls'] ?? []);

        return view('pets.edit', [
           'pet' => $pet,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePetRequest $request, string $id)
    {
        $validated = $request->validated();

        $photoUrls = [];
        if (!empty($validated['photo_urls'])) {
            $pattern = '/[\r\n,;]+/';  // Add more separators as needed
            $photoUrls = collect(preg_split($pattern, $validated['photo_urls']))
                            ->map(fn ($line) => trim($line))
                            ->filter()
                            ->values()
                            ->all();
        }

        $tags = [];
        if (!empty($validated['tags'])) {
            $tags = collect(explode(',', $validated['tags']))
                ->map(fn ($tag) => trim($tag))
                ->filter()
                ->map(fn ($name) => ['name' => $name])
                ->values()
                ->all();
        }

        $category = [];
        if (!empty($validated['category_name'])) {
            $category = [
                'id' => random_int(1, 100), // nie mamy id, a api chce int
                'name' => $validated['category_name'],
            ];
        }

        $petPayload = [
            'id' => $id,
            'category' => $category,
            'name' => $validated['name'],
            'photoUrls' => $photoUrls,
            'tags' => $tags,
            'status' => $validated['status'],
        ];

        // wysłanie do API POST
        try {
            $updatedPet = $this->petstoreClient->updatePet($petPayload);
        } catch (ConnectionException) {
            return back()
                ->withInput()
                ->with('error', 'Nie udało się połączyć z API Petstore podczas aktualizacji danych zwierzaka.');
        } catch (RequestException $e) {
            dd($e);
            return back()
                ->withInput()
                ->with('error', 'API Petstore zwróciło błąd podczas aktualizacji danych zwierzaka.');
        }

        return redirect()
            ->route('pets.show', $updatedPet['id'])
            ->with('success', "Dane zwierzaka zaktualizowane.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        try {
            $this->petstoreClient->deletePet($id);
        } catch (ConnectionException) {
            return back()
                ->with('error', 'Nie udało się połączyć z API Petstore podczas usuwania zwierzaka.');
        } catch (RequestException $e) {
            $status = $e->response?->status();

            if ($status === 404) {
                return redirect()
                    ->route('pets.index')
                    ->with('error', "Zwierzak o ID {$id} nie został znaleziony w API (nie można usunąć).");
            }

            // na chwilę do debugowania możesz odkomentować:
            // dd($status, $e->response?->body());

            return back()
                ->with('error', 'API Petstore zwróciło błąd podczas usuwania zwierzaka.');
        }

        $status = $request->get('status', 'available');
        return redirect()
            ->route('pets.index', ['status' => $status])
            ->with('success', "Dane zwierzaka id: {$id} zostały usunięte.");
    }

    /**
     * Helper nested array values 2string
     */
    private function implodeField(array $pet, string $field, string $key = 'name'): string
    {
        if (!isset($pet[$field]) || !is_array($pet[$field])) {
            return '';
        }

        return collect($pet[$field])
            ->pluck($key)
            ->filter()
            ->implode(', ');
    }

    /**
     * Helper array values 2string
     */
    private function implodeSimple(array $values): string
    {
        if (!is_array($values)) {
            return '';
        }

        return collect($values)
            ->filter()
            ->implode(', ');
    }
}
