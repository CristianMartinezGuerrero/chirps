<?php

namespace App\Models;

use App\Events\ChirpCreated;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chirp extends Model
{
    use HasFactory;
    private ?string $image;

    protected $fillable = [
        'user_id',
        'message',
        'image'
    ];

    protected $dispatchesEvents = [
        'created' => ChirpCreated::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function getImage(): ?string
    {
        return $this->image;
    }
    public function getMessage(): string 
    {
        if (!$this->message) {
            $cortado = "No message";
        } else
            $cortado = substr($this->message, 0, 50);
        return $cortado;
        // return $this->message;
    }
}
