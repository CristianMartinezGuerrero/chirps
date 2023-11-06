<?php

namespace App\Http\Service;
use App\Models\Chirp;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ChirpService
{
    public function store(int $userId, string $message, UploadedFile $file = null) : void
    {
        Chirp::create([
            'user_id' => $userId,
            'message' => $message,
            'image'   => self::saveImage($file)
        ]);
    }

    public function update(Chirp $chirp, string $newMessage, UploadedFile $file = null) : void
    {
        $path = self::saveImage($file);
        if ($path != null){
            Storage::disk("public")->delete($chirp->image);
        }
        $chirp->update([
            'message' => $newMessage,
            'image'   => $path ?: $chirp->image
         ]);
    }
    private function saveImage(UploadedFile $file = null) : string{
        $path = "";
        if ($file != null){
            $pathSaved = Storage::putFile('public' ,$file);
            $path = explode("/", $pathSaved)[1];
        }

        return $path;
    }
    public function destroy(Chirp $chirp) : void
    {
        if($chirp->image != null){
            Storage::disk("public")->delete($chirp->image);
        }
        $chirp->delete();
    } 
//    public function deleteImage(Chirp $chirp) : void
//    {
//        if($path != null){
//            Storage::disk("public")->delete($chirp->image);
//        }
//    }
}
