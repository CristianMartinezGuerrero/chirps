<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Models\Chirp;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use App\HTTP\Controllers\LOgs;
use App\Http\Requests\ChirpRequest;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isEmpty;

class ChirpController extends Controller
{
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
        // $request->saveImage($request);
        $path = Storage::putFile('image', $request->file('image'));
        Storage::putFile('/public/image', $request->file('image'));
        $request->user()->chirps()->create(['message' => $request->input('message'), 'image' => $path]);

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

        app('log')->info("Request Captured", $request->all());

        // $validated = $request->validate([
        //     'message' => 'required|string|max:255',
        //     'image'  => 'string'
        // ]);
        // $validate = $request->payload();
        // $request->updateImage($request);
        // dd($request->payload());
        // dd($chirp);
        dd($request);
        $chirp->update($request->payload());
        // $chirp->update($validated);

        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp): RedirectResponse
    {
        $this->authorize('delete', $chirp);

        $chirp->delete();

        return redirect(route('chirps.index'));
    }
}
