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
        $pets = $this->petstoreClient->findPetsByStatus($status);

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

        $createdPet = $this->petstoreClient->createPet($petPayload);

        return redirect()
        ->route('pets.show', $createdPet['id'])
        ->with('success', "Zwierzak dodany.");
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $pet = $this->petstoreClient->findPetById($id);

        return view('pets.show', [
            'pet' => $pet,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $pet = $this->petstoreClient->findPetById($id);
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

        $updatedPet = $this->petstoreClient->updatePet($petPayload);

        return redirect()
            ->route('pets.show', $updatedPet['id'])
            ->with('success', "Dane zwierzaka zaktualizowane.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id, Request $request)
    {
        $this->petstoreClient->deletePet($id);

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
        return collect($values)
            ->filter()
            ->implode(', ');
    }
}
