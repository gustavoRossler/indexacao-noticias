<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImporterController extends Controller
{
    public function testElasticsearchConnection()
    {
        try {
            $canPing = \Elasticsearch::ping();
            return response()->json([
                "success" => true,
                "canPing" => $canPing
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    public function getElasticsearchIndexStatus()
    {
        try {
            $stats = \Elasticsearch::indices()->stats(['index' => 'news']);
            return response()->json([
                "success" => true,
                "stats" => $stats
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    public function getElasticsearchIndexDocuments()
    {
        $response = \Elasticsearch::search([
            'index' => 'news',
            'size' => 999,
        ]);

        $docs = [];

        foreach ($response['hits']['hits'] as $hit) {
            $docs[] = [
                'id' => $hit['_id'],
                'title' => $hit['_source']['title'],
                'subtitle' => $hit['_source']['subtitle'],
                'content' => $hit['_source']['content'],
                'source' => $hit['_source']['source'],
                'url' => $hit['_source']['url'],
                'publication_date' => $hit['_source']['publication_date'],
            ];
        }

        return $docs;
    }

    public function listElasticsearchIndexDocuments()
    {
        try {
            $docs = $this->listElasticsearchIndexDocuments();

            return response()->json([
                "success" => true,
                "response" => $docs
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    public function getElasticsearchNodesStats()
    {
        try {
            $stats = \Elasticsearch::nodes()->stats();
            return response()->json([
                "success" => true,
                "stats" => $stats
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    public function getElasticsearchClusterStats()
    {
        try {
            $stats = \Elasticsearch::cluster()->stats();
            return response()->json([
                "success" => true,
                "stats" => $stats
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    public function deleteElasticsearchIndex()
    {
        try {
            $response = \Elasticsearch::indices()->delete(['index' => 'news']);
            return response()->json([
                "success" => true,
                "response" => $response
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    public function importData()
    {
        try {
            $newsToImport = \App\Models\News::where('imported', false)->get();
            $resultInserts = [];
            foreach ($newsToImport as $news) {
                $resInsert = \Elasticsearch::index([
                    'body' => [
                        'title' => $news->title,
                        'subtitle' => $news->subtitle,
                        'content' => $news->content,
                        'source' => $news->source,
                        'url' => $news->url,
                        'publication_date' => $news->publication_date,
                    ],
                    'index' => 'news',
                    'id' => $news->id,
                ]);
                $resultInserts[] = $resInsert;
            }
            return response()->json([
                "success" => true,
                "resultInserts" => $resultInserts,
                "newsToImport" => $newsToImport,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
            ], 400);
        }
    }
}
