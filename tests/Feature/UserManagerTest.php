<?php

use WebFresh\UserManager\Tests\TestCase;

test('confirm environment is set to testing', function () {
    expect(config('app.env'))->toBe('testing');
})->uses(TestCase::class);
