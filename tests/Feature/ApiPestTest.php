<?php


use Illuminate\Support\Facades\Http;

test('login', function () {
    // Arrange
    Http::fake([
        'https://api.prezo.info/login' => Http::response(["token" => "randomtokenforuser"]),
    ]);

    // Act
    $response = Http::post('https://api.prezo.info/login', ["username" => "user", "password" => "user1234"]);
    
    // Assert
    expect($response->json())
        ->not->toBeEmpty()
        ->toBeArray();
});

test('restaurants get', function () {
    // Arrange
    Http::fake([
        'https://api.prezo.info/restaurants' => Http::response([
            ["id" => 1, "name" => "Mac Donalds"],
            ["id" => 2, "name" => "Big Donalds"]
        ]),
    ]);

    // Act
    $response = Http::withToken("randomtokenforuser")->get('https://api.prezo.info/restaurants');
    
    // Assert
    expect($response->json())
        ->not->toBeEmpty()
        ->toBeArray();
});

test('restaurants post', function () {
    // Arrange

    $token = "randomtokenforuser";

    Http::fake([
        'https://api.prezo.info/restaurants' => Http::response([
            "id" => 2, 
            "name" => "Nombre", 
            "address" => "Alguna calle y ciudad", 
            "nif" => "B66664521", 
            "phone" => "123456789"
        ]),
    ]);

    // Act
    $response = Http::withToken($token)->post('https://api.prezo.info/restaurants');
    // Assert
    
    expect($response->json())
        ->not->toBeEmpty()
        ->toBeArray();
});