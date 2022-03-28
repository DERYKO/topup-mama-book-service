<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public $client;

    /**
     * BookController constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    public function index(Request $request)
    {
        try {
            $response = $this->client->get('https://www.anapioficeandfire.com/api/books', [
                'params' => $request->all()
            ]);
            $records = json_decode($response->getBody()->getContents());
            $records = collect($records)->map(function ($book) {
                $book->comments_count = 0;
                return $book;
            });
            return response()->json([
                'message' => 'success fetching books',
                'data' => $records
            ]);
        } catch (ClientException $e) {
            return response()->json([
                'message' => 'error fetching books',
                'error' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $response = $this->client->get('https://www.anapioficeandfire.com/api/books/' . $id, [
                'params' => $request->all()
            ]);
            $record = json_decode($response->getBody()->getContents());
            return response()->json([
                'message' => 'success fetching books',
                'data' => $record
            ]);
        } catch (ClientException $e) {
            return response()->json([
                'message' => 'error fetching books',
                'error' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
