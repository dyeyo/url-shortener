<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @OA\Info(title="URL Shortener API", version="1.0")
 */
class UrlController extends Controller
{
     /**
     * @OA\Post(
     *     path="/api/shorten",
     *     operationId="shortenUrl",
     *     tags={"URLs"},
     *     summary="Acortar una URL",
     *     @OA\RequestBody(
     *         description="URL a ser acortada",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="original_url", type="string", example="https://www.rfc-editor.org/rfc/rfc1738")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="URL acortada creada con éxito",
     *         @OA\JsonContent(
     *             @OA\Property(property="shortened_url", type="string", example="http://127.0.0.1:8001/b1wyI9He")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function shorten(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url'
        ]);

        $originalUrl = $request->input('original_url');

        // Recortar URL unica
        $shortenedUrl = $this->generateUniqueShortenedUrl();

        $url = Url::create([
            'original_url' => $originalUrl,
            'shortened_url' => $shortenedUrl,
        ]);

        return response()->json(['shortened_url' => url($shortenedUrl)], 201);
    }

    private function generateUniqueShortenedUrl()
    {
        do {
            $shortenedUrl = Str::random(8);
        } while (Url::where('shortened_url', $shortenedUrl)->exists());

        return $shortenedUrl;
    }

    /**
     * @OA\Get(
     *     path="/{shortened_url}",
     *     operationId="redirectUrl",
     *     tags={"URLs"},
     *     summary="Redirigir a la URL original usando la URL acortada",
     *     @OA\Parameter(
     *         name="shortened_url",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirección a la URL original"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="URL no encontrada"
     *     )
     * )
     */
    public function redirect($shortened_url)
    {
        $url = Url::where('shortened_url', $shortened_url)->firstOrFail();
        return redirect($url->original_url);
    }
}
