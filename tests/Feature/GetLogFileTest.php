<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetLogFileTest extends TestCase
{

    public function test_get_log_file()
    {
        $response = $this->withoutExceptionHandling()->post('/api/get-file', [
            "path"  => "C:\Temp\log.txt",
            "token" => "1|fykCQIePp5v7V4vMblfp2n0BtnTZSiDGIieKhwfZ",
            "page" => 5000
        ]);

        $response->assertStatus(200);
    }
}
