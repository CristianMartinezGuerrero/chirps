<?php

namespace App\Http\Requests;

use http\Env\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class ChirpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'message' => 'required|string|max:255',
            'image' =>  'image|mimes:jpeg,png,jpg,webp|max:11000',
        ];
    }
    //  public function saveImage($request)
    //  {
    //      $path_saved = Storage::putFile('public' , $request->file('image'));
    //      $path = 'storage/'.explode("/", $path_saved)[1];
    //      return $path;

    //  }
    public function payload(): array
    {
        return $this->only(['message', 'image']);
    }
}
