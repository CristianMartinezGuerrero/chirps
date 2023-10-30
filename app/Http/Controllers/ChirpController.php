<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Models\Chirp;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use App\HTTP\Controllers\LOgs;
use App\Http\Requests\ChirpRequest;

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
        // $image = null;
       if($request->hasFile('upload')){
             $image = base64_encode(file_get_contents($request->file('upload')));
             $request->request->add(['image' => $image]); //add request
        };

        // $validated = $request->validate([
        //     'message' => 'required|string|max:255',
        //     'image' => 'string'
        // ]);
        // dd($request->payload());
        // Chirp::create($request->payload());
         $request->user()->chirps()->create($request->payload());
        // $path = $request->photo->store('images');
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
    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
        $this->authorize('update', $chirp);

        app('log')->info("Request Captured", $request->all());

        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $chirp->update($validated);

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
