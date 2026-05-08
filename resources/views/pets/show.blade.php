@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">
        Szczegóły zwierzaka #{{ $pet['id'] ?? '-' }}
    </h1>
    <div class="bg-white border border-gray-300 p-4">
        <p class="mb-2">
            <strong>Nazwa:</strong>
            {{ $pet['name'] ?? '-' }}
        </p>
        <p class="mb-2">
            <strong>Status:</strong>
            {{ $pet['status'] ?? '-' }}
        </p>
        <p class="mb-2">
            <strong>Kategoria:</strong>
            @if (isset($pet['category']))
                ID: {{ $pet['category']['id'] ?? '-' }},
                Nazwa: {{ $pet['category']['name'] ?? '-' }}
            @else
                -
            @endif
        </p>
        <div class="mb-2">
            <strong>Photo URLs:</strong>
            @if (!empty($pet['photoUrls']))
                <ul class="list-disc list-inside">
                    @foreach ($pet['photoUrls'] as $url)
                        <li>
                            <a href="{{ $url }}" target="_blank" class="text-blue-600 hover:underline">
                                {{ $url }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>-</p>
            @endif
        </div>
        <div class="mb-2">
            <strong>Tagi:</strong>
            @if (!empty($pet['tags']))
                <ul class="list-disc list-inside">
                    @foreach ($pet['tags'] as $tag)
                        <li>
                            ID: {{ $tag['id'] ?? '-' }},
                            Nazwa: {{ $tag['name'] ?? '-' }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p>-</p>
            @endif
        </div>
    </div>
    <a href="{{ route('pets.index') }}" class="inline-block mt-4 text-blue-600 hover:underline text-sm">
        ← Powrót do listy
    </a>
@endsection
