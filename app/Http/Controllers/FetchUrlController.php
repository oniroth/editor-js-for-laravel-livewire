<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FetchUrlController extends Controller
{
    //$url = request('url');
    public function fetch(Request $request)
    {
        $url = request('url');

        if (!$url) {
            return response()->json(['success' => 0, 'message' => 'URL is required'], 400);
        }

        try {
            $response = Http::get($url);
            $html = $response->body();

            preg_match('/<title>(.*?)<\/title>/', $html, $titleMatches);
            preg_match('/<meta\s+name=["\']description["\']\s+content=["\'](.*?)["\']/', $html, $descMatches);
            preg_match('/<meta\s+property=["\']og:image["\']\s+content=["\'](.*?)["\']/', $html, $imageMatches); // اضافه کردن استخراج og:image

            $title = $titleMatches[1] ?? '';
            $description = $descMatches[1] ?? '';
            $imageUrl = $imageMatches[1] ?? 'https://via.placeholder.com/150';

            return response()->json([
                'success' => 1,
                'meta' => [
                    'title' => $title,
                    'description' => $description,
                    'image' => [
                        'url' => $imageUrl
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => 0, 'message' => $e->getMessage()], 500);
        }
    }
}
