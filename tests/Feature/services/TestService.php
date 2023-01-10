<?php

namespace Tests\Feature\services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestService extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function msg()
    {
        return "message from test service";
    }
}
