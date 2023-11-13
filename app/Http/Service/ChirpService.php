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

    public function getStats(array $chirps) : array {

        $total = $max = $average = 0;
        $min = 1000;

        if (!empty($chirps)) {
            foreach ($chirps as $chirp) {
                $total += $chirp;
                if ($min > $chirp) {
                    $min = $chirp;
                }
                if ($max < $chirp) {
                    $max = $chirp;
                }
            }
        }

        $stats = [
            'max' => $max,
            'min' => $min,
            'average' => $total/count($chirps),
            'total' => $total,
        ];
        return $stats;
    }

    public function getUsersStats(array $statsUsers) : array {
        
        $max = $average = $minUser = $maxUser = $averageUser = 0;
        $min = 1000;

        if (!empty($statsUsers)) {
            foreach ($statsUsers as $id => $user) {
                if ($min > $user['min']) {
                    $min = $user['min'];
                    $minUser = $id;
                }
                if ($max < $user['max']) {
                    $max = $user['max'];
                    $maxUser = $id;
                }
                if ($average < $user['average']) {
                    $average = $user['average'];
                    $averageUser = $id;
                }
            }
        }

        $usersStats = [
            'maxUser' => $maxUser,
            'minUser' => $minUser,
            'averageUser' => $averageUser
        ];
        return $usersStats;
    }
}