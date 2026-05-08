@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Dodaj nowego zwierza</h1>

    <form action="{{ route('pets.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium mb-1">Nazwa</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
        </div>

        <div>
            <label for="status" class="block text-sm font-medium mb-1">Status</label>
            <select id="status" name="status" required
                class="w-full border border-gray-300 rounded px-2 py-1 text-sm bg-white">
                <option value="">-- wybierz --</option>
                <option value="available" {{ old('status') === 'available' ? 'selected' : '' }}>available</option>
                <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>pending</option>
                <option value="sold" {{ old('status') === 'sold' ? 'selected' : '' }}>sold</option>
            </select>
        </div>

        <div class="flex items-center gap-2 pt-2">
            <button type="submit" class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                Zapisz
            </button>
            <a href="{{ route('pets.index') }}"
                class="px-3 py-1 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-100">
                Anuluj
            </a>
        </div>
    </form>
@endsection
