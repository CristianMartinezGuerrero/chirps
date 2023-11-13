<?php


use App\Http\Service\ChirpService;

test('info chirps', function () {
    // Arrage
    $chirps = [1,2,3,4,5,6,7,8,9,10,11,12];

    // Act
    $stats = app(ChirpService::class)->getStats($chirps);

    // Assert
    expect($stats)
        ->toBeArray()
        ->not->toBeEmpty()
        ->toHaveCount(4);

    expect($stats['max'])->toBe(12);
    expect($stats['min'])->toBe(1);
    expect($stats['average'])->toBe(6.5);
    expect($stats['total'])->toBe(78);

});

test('info users', function () {
    // Arrage
    $statsUsers = [
        1 => ['total' => 2, 'min'=> 1, 'max' => 1, 'average' => 0.1],
        2 => ['total' => 10, 'min'=> 2, 'max' => 40, 'average' => 0,8],
        3 => ['total' => 90, 'min'=> 10, 'max' => 30, 'average' => 7,5],
        4 => ['total' => 8, 'min'=> 0, 'max' => 3, 'average' => 0,6],
    ];

    // Act
    $results = app(ChirpService::class)->getUsersStats($statsUsers);

    // Assert
    expect($results)
        ->toBeArray()
        ->not->toBeEmpty()
        ->toHaveCount(3);
    expect($results['maxUser'])->toBe(2);
    expect($results['minUser'])->toBe(4);
    expect($results['averageUser'])->toBe(3);

});