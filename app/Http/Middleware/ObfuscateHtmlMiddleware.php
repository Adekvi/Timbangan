<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ObfuscateHtmlMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (
            $response instanceof Response &&
            str_contains($response->headers->get('Content-Type') ?? '', 'text/html') &&
            !str_contains($request->getPathInfo(), '/admin')
        ) {
            $html = $response->getContent();

            // 1. SIMPAN <script>, <style>, <pre>, <code> → JANGAN DIUBAH SAMA SEKALI
            $safeBlocks = [];
            $html = preg_replace_callback(
                '#<(script|style|pre|code)(\s+[^>]*)?>.*?</\1>#is',
                function ($matches) use (&$safeBlocks) {
                    $key = "__SAFE_BLOCK_" . count($safeBlocks) . "__";
                    $safeBlocks[$key] = $matches[0]; // Simpan ASLI, JANGAN DIUBAH!
                    return $key;
                },
                $html
            );

            // 2. Hapus komentar HTML
            $html = preg_replace('/<!--(?!\[if).*?-->/s', '', $html);

            // 3. Acak urutan atribut (aman untuk HTML)
            $html = preg_replace_callback(
                '#<([a-zA-Z0-9:-]+)([^>]*)>#',
                function ($m) {
                    $tag = $m[1];
                    $attrs = $m[2];

                    if (empty(trim($attrs))) return "<$tag>";

                    preg_match_all('#\s+([a-zA-Z0-9:-]+)=(["\'])(.*?)\2#', $attrs, $matches, PREG_SET_ORDER);
                    $attrList = array_map(fn($match) => $match[1] . '=' . $match[2] . $match[3] . $match[2], $matches);

                    shuffle($attrList);

                    return '<' . $tag . ' ' . implode(' ', $attrList) . '>';
                },
                $html
            );

            // 4. Minify HTML (tapi HANYA DI LUAR safe blocks)
            $html = preg_replace('/\s+/', ' ', $html);
            $html = trim($html);

            // 5. Tambah spasi acak antar tag
            $html = preg_replace_callback('#>#', fn() => '>' . str_repeat(' ', mt_rand(0, 1)), $html);

            // 6. KEMBALIKAN <script> dan <style> ASLI (TIDAK DIMODIFIKASI)
            foreach ($safeBlocks as $key => $originalBlock) {
                $html = str_replace($key, $originalBlock, $html);
            }

            // 7. Tambah noise
            $noise = substr(base64_encode(random_bytes(8)), 0, 10);
            $html .= "<!--obf:$noise-->";

            $response->setContent($html);
        }

        return $response;
    }
}
