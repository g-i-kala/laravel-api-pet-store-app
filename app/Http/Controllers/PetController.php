<?php

namespace App\Http\Controllers;

use App\Clients\PetStoreClient;
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
        // narazie na sztywno
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
    public function store(Request $request)
    {
        // pozniej do FormRequest
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'category_name' => ['nullable', 'string', 'max:255'],
            'photo_urls'    => ['nullable', 'string'],
            'tags'          => ['nullable', 'string'],
            'status'        => ['required', 'in:available,pending,sold'],
        ]);

        $photoUrls = [];
        if (!empty($validated['photo_urls'])) {
            $photoUrls = collect(preg_split('/\r\n|\r|\n/', $validated['photo_urls']))
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

        if (isset($pet['tags']) && is_array($pet['tags'])) {
            $tagNames = collect($pet['tags'])
                ->pluck('name')
                ->implode(', ');
            $pet['tags_string'] = $tagNames;
        } else {
            $pet['tags_string'] = '';
        };

        if (isset($pet['photoUrls']) && is_array($pet['photoUrls'])) {
            $tagNames = collect($pet['photoUrls'])
                ->pluck('name')
                ->implode(', ');
            $pet['photoUrls_string'] = $tagNames;
        } else {
            $pet['photoUrls_string'] = '';
        }

        return view('pets.edit', [
           'pet' => $pet,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // pozniej do FormRequest
        $validated = $request->validate([
            'id'            => ['required', 'integer'],
            'name'          => ['required', 'string', 'max:255'],
            'category_name' => ['nullable', 'string', 'max:255'],
            'photo_urls'    => ['nullable', 'string'],
            'tags'          => ['nullable', 'string'],
            'status'        => ['required', 'in:available,pending,sold'],
        ]);

        $photoUrls = [];
        if (!empty($validated['photo_urls'])) {
            $photoUrls = collect(preg_split('/\r\n|\r|\n/', $validated['photo_urls']))
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

}
