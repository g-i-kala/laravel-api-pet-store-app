@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Dodaj nowego zwierza</h1>

    <form action="{{ route('pets.update', $pet['id']) }}" method="POST" class="bg-white border border-gray-300 p-4">
        @csrf
        <div class="mb-3">
            <label for="name" class="block text-sm font-medium text-gray-700">Nazwa *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $pet['name'] ?? '') }}"
                class="mt-1 block w-full border border-gray-300 px-2 py-1 text-sm" required>
        </div>
        <div class="mb-3">
            <label for="category_name" class="block text-sm font-medium text-gray-700">
                Kategoria (opcjonalnie)
            </label>
            <input type="text" id="category_name" name="category_name"
                value="{{ old('category_name', $pet['category']['name'] ?? '') }}"
                class="mt-1 block w-full border border-gray-300 px-2 py-1 text-sm" placeholder="np. Dogs">
        </div>
        <div class="mb-3">
            <label for="photo_urls" class="block text-sm font-medium text-gray-700">
                Photo URLs (opcjonalnie)
            </label>
            <textarea id="photo_urls" name="photo_urls" rows="3"
                class="mt-1 block w-full border border-gray-300 px-2 py-1 text-sm" placeholder="Każdy URL w osobnej linii">{{ old('photo_urls', $pet['photo_urls'] ?? '') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="tags" class="block text-sm font-medium text-gray-700">
                Tagi (opcjonalnie)
            </label>
            <input type="text" id="tags" name="tags" value="{{ old('tags', $pet['tags_string'] ?? '') }}"
                class="mt-1 block w-full border border-gray-300 px-2 py-1 text-sm" placeholder="np. small, friendly">
        </div>
        <div class="mb-3">
            <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
            <select id="status" name="status" class="mt-1 block w-full border border-gray-300 px-2 py-1 text-sm"
                required>
                <option value="">-- wybierz --</option>
                @foreach (['available', 'pending', 'sold'] as $s)
                    <option value="{{ $s }}" {{ old('status') === $s ? 'selected' : '' }}>
                        {{ $s }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 text-white text-sm rounded">
            Zapisz
        </button>
    </form>
@endsection
