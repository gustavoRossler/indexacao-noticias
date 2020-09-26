<?php

namespace Tests\Feature;

use Facade\FlareClient\Stacktrace\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ElasticsearchTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    /**
     * Can access home page
     *
     * @return void
     */
    public function test_can_ping_elasticsearch_host()
    {
        $response = $this->get('/api/importer/test-connection');
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'canPing' => true
        ]);
    }

}
