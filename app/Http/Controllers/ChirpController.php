<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Models\Chirp;
use Illuminate\View\View;
use App\Http\Requests\ChirpRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Service\ChirpService;

class ChirpController extends Controller
{
    public function __construct(protected ChirpService $chirpService)
    {
    }
    /**
     * Display a listing of the resource.
     */

    public function index(): View
    {
        return view('chirps.index', [
            'chirps' => Chirp::with('user')->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ChirpRequest $request): RedirectResponse
    {
       $this->chirpService->store(
            $request->user()->id,
            $request->validated('message'),
            $request->validated('image')
        );
        return redirect(route('chirps.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp): View
    {
        $this->authorize('update', $chirp);

        return view('chirps.edit', [
            'chirp' => $chirp,
        ]);

    }
    /**
     * Update the specified resource in storage.
     */
    public function update(ChirpRequest $request, Chirp $chirp): RedirectResponse
    {
        $this->authorize('update', $chirp);

        $this->chirpService->update(
            $chirp,
            $request->validated('message'),
            $request->validated('image')
        );

        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp): RedirectResponse
    {
        $this->authorize('delete', $chirp);

        $this->chirpService->destroy($chirp);

        return redirect(route('chirps.index'));
    }
}
