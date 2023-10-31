<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

    // public function saveImage($request) 
    // {
    //     if($request->hasFile('upload')){
    //         $image = base64_encode(file_get_contents($request->file('upload')));
    //         $request->request->add(['image' => $image]); //add request
    //     };
    // }
    // public function updateImage($request) 
    // {
    //     if($request->hasFile('upload')){
    //         $image = ($request->file('upload'));
    //         $request->request->add(['image' => $image]); //add request
    //     };
    // }
    public function payload(): array
    {
        return $this->only(['message', 'image']);
    }
}
