<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class NewsController extends Controller
{
    public function __construct()
    {
        try {
            $hasMigrations = Db::getSchemaBuilder()->hasTable('migrations');
            if (!$hasMigrations) {
                \Artisan::call('migrate:install');
                \Artisan::call('migrate');
            }
        } catch (\Exception $e) {
            echo "Error trying to create the database tables";
        }
    }

    public function getList()
    {
        try {
            $importer = new \App\Http\Controllers\ImporterController();
            $documents = $importer->getElasticsearchIndexDocuments();
            return view('news/index', [
                'documents' => $documents
            ]);
        } catch (\Exception $e) {
            return view('news/index');
        }
    }

    public function uploadFile(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'file' => 'required|mimes:json,txt|max:2048',
                ]
            );

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            if ($files = $request->file('file')) {

                //store file into document folder
                $file = $request->file->store('public/documents');

                $dataProcessed = $this->processData(\Storage::get($file));

                return response()->json([
                    "success" => true,
                    "message" => "File successfully uploaded",
                    "dataProcessed" => $dataProcessed
                ], 201);
            }
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    public function processData($jsonData)
    {
        if (!$this->isValidJson($jsonData)) {
            throw new \Exception('Invalid json data in the file');
        }

        $data = json_decode($jsonData);

        $dataProcessed = [];

        foreach ($data as $obj) {
            $news = \App\Models\News::create([
                'title' => $obj->titulo,
                'subtitle' => $obj->subtitulo ?? '',
                'content' => $obj->conteudo ?? '',
                'source' => $obj->fonte ?? '',
                'url' => $obj->url ?? '',
                'publication_date' => $obj->data_publicacao ?? '0000-00-00 00:00:00'
            ]);
            $dataProcessed[] = $news;
        }

        return $dataProcessed;
    }

    public function isValidJson($json)
    {
        json_decode($json);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
