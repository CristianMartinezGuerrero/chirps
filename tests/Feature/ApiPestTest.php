<?php


use Illuminate\Support\Facades\Http;
use App\Http\Service\ApiService;
use Illuminate\Http\Client\Request;

beforeEach(function() {
    Http::preventStrayRequests();
});

test('login', function () {
    // Arrange
    $data = [
        "username" => "user", 
        "password" => "user1234"
    ];
    $token = 'token';

    $expectResponse = ['token' => 'asd'];
    Http::fake([
        'https://api.prezo.info/login' => Http::response($expectResponse),
    ]);

    // Act
    $apiService = new ApiService($token);
    $response = $apiService->prezoLogin($data);
    
    // Assert
    Http::assertSent(function (Request $request) use ($token) {
        return $request->method() === 'POST' &&
            $request->url() == 'https://api.prezo.info/login' &&
            $request['username'] == 'user' &&
            $request['password'] == 'user1234';
    });
    expect($response)->toMatchArray($expectResponse);
});

test('restaurants get', function () {
    // Arrange
    $data = [
        ["id" => 1, "name" => "Mac Donalds"],
        ["id" => 2, "name" => "Big Donalds"]
    ];
    $token = 'token';
    Http::fake([
        'https://api.prezo.info/restaurants' => Http::response($data),
    ]);

    // Act
    $apiService = new ApiService($token);
    $response = $apiService->getRestaurants();
    
    // Assert
    Http::assertSent(function (Request $request) use ($token) {
        return $request->hasHeader('Authorization', 'Bearer ' . $token) &&
            $request->url() == 'https://api.prezo.info/restaurants';
    });
    expect($response)->toMatchArray($data);
});

test('restaurants post', function () {
    
    // Arrange
    $data = [
        'name' => 'Nombre',
        'address' => 'Somewhere',
        'nif' => 'b7858755',
        'phone' => '0739834'
    ];
    $expectedArray = array_merge($data, ['id' => 2]);
    $token = 'token';

    Http::fake([
        'https://api.prezo.info/restaurants' => Http::response($expectedArray),
    ]);

    // Act
    $apiService = new ApiService($token);
    $response = $apiService->createRestaurant($data);

    // Assert
    Http::assertSent(function (Request $request) {
        return $request->hasHeader('Authorization', 'Bearer token') &&
            $request->url() == 'https://api.prezo.info/restaurants' &&
            $request['name'] == 'Nombre' &&
            $request['address'] == 'Somewhere'&&
            $request['nif'] == 'b7858755' &&
            $request['phone'] == '0739834' ;
    });
    expect($response)->toMatchArray($expectedArray);
});