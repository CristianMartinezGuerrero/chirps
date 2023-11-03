<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Models\Chirp;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use App\Http\Controllers\Log;
use App\Http\Requests\ChirpRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Avatar;
use App\Http\Services\ChirpService;

use function PHPUnit\Framework\isEmpty;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $chirpService;

    public function __construct(ChirpService $chirpService)
    {
        $this->chirpService = $chirpService;
    }


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

        // $path = null;
        // if($request->hasFile("image")){
        //     $path = $request->saveImage($request);
        // }

        // $request->user()->chirps()->create(['message' => $request->input('message'), 'image' => $path]);


        // return redirect(route('chirps.index'));

        $path = $this->chirpService->saveImage($request);

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

        // $this->authorize('update', $chirp);
        // $path = null;
        // if($request->hasFile("image" )){
        //     $path = $request->saveImage($request);
        //     Storage::delete($chirp->image);
        // }
        // $path = $path ?: $chirp->image;

        // app('log')->info("Request Captured", $request->all());
        // $chirp->update(['message' => $request->input('message'), 'image' => $path]);
        // // $chirp->update($validated);

        // return redirect(route('chirps.index'));

        $this->authorize('update', $chirp);
        $this->chirpService->updateChirp($chirp, $request);

        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp): RedirectResponse
    {
        // $this->authorize('delete', $chirp);
        // if($chirp->image){
        //     Storage::delete($chirp->image);
        // }
        // $chirp->delete();

        // return redirect(route('chirps.index'));

        $this->authorize('delete', $chirp);
        $this->chirpService->deleteChirp($chirp);

        return redirect(route('chirps.index'));
    }
}
