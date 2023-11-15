<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ChirpRequest;
use App\Models\Chirp;

class ChirpService
{
    public function saveImage(ChirpRequest $request)
    {
        $path = null;

        if ($request->hasFile("image")) {
            $path = $this->storeImage($request->file("image"));
        }

        return $path;
    }

    public function storeImage($image)
    {
        $path_saved = Storage::putFile('public' , $image);
        $path = 'storage/'.explode("/", $path_saved)[1];
        return $path;
    }

    public function updateChirp(Chirp $chirp, ChirpRequest $request)
    {
        $path = null;

        if ($request->hasFile("image")) {
            $path = $this->storeImage($request->file("image"));
            Storage::delete($chirp->image);
        }

        $path = $path ?: $chirp->image;

        $chirp->update(['message' => $request->input('message'), 'image' => $path]);
    }

    public function deleteChirp(Chirp $chirp)
    {
        if ($chirp->image) {
            Storage::delete($chirp->image);
        }

        $chirp->delete();
    }
}