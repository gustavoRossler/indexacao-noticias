<?php

namespace Tests\Feature;

use Facade\FlareClient\Stacktrace\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    /**
     * Can access home page
     *
     * @return void
     */
    public function test_can_access_home_page()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Can access home page
     *
     * @return void
     */
    public function test_can_access_news_page()
    {
        $response = $this->get('/news');
        $response->assertStatus(200);
    }

    /**
     * Can send file to API
     *
     * @return void
     */
    public function test_can_upload_file_to_api()
    {
        $uploadedFile = new UploadedFile(storage_path('test/noticias.json'), 'test-file.json', 'application/json', null, TRUE);

        $response = $this->json('POST', '/api/upload-file', [
            'file' => $uploadedFile,
        ]);

        Storage::assertExists('public/documents/' . $uploadedFile->hashName());

        Storage::delete('public/documents/' . $uploadedFile->hashName());

        $response->assertStatus(201);

        $response->assertJsonFragment([
            'success' => true
        ]);

        $response->assertJsonStructure([
            'dataProcessed' => [
                [
                    'id',
                    'title',
                    'subtitle',
                    'content',
                    'source',
                    'url',
                    'publication_date',
                    'created_at',
                    'updated_at',
                ]
            ],
        ]);
    }

    /**
     * Can send file to API
     *
     * @return void
     */
    public function test_invalid_json_data()
    {
        $uploadedFile = new UploadedFile(storage_path('test/noticias_inv.json'), 'test-file.json', 'application/json', null, TRUE);

        $response = $this->json('POST', '/api/upload-file', [
            'file' => $uploadedFile,
        ]);

        Storage::assertExists('public/documents/' . $uploadedFile->hashName());

        Storage::delete('public/documents/' . $uploadedFile->hashName());

        $response->assertStatus(400);

        $response->assertJsonFragment([
            'success' => false,
            'message' => 'Invalid json data in the file'
        ]);
    }
}
