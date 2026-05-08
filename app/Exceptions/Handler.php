<?php

namespace App\Exceptions;

use App\Exceptions\PetstoreException;
use Illuminate\Foundation\Exceptions\Handler as BaseHandler;
use Illuminate\Http\Request;

class Handler extends BaseHandler
{
    public function register(): void
    {
        $this->renderable(function (PetstoreException $e, Request $request) {

            // dla ścieżek /pets* – wróć na listę z komunikatem
            if ($request->is('pets*')) {
                return redirect()
                    ->route('pets.index', ['status' => 'available'])
                    ->with('error', $e->getMessage());
            }

            // fallback
            return redirect('/')
                ->with('error', $e->getMessage());
        });
    }

}
