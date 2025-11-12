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

        // Hanya proses HTML
        if (
            $response instanceof Response &&
            str_contains($response->headers->get('Content-Type') ?? '', 'text/html') &&
            !str_contains($request->getPathInfo(), '/admin') // skip panel admin
        ) {
            $html = $response->getContent();

            // 1. Simpan <style>, <script>, <pre>, <code> → aman
            $safeBlocks = [];
            $html = preg_replace_callback(
                '#<(style|script|pre|code)(\s+[^>]*)?>.*?</\1>#is',
                function ($matches) use (&$safeBlocks) {
                    $key = "__OBFUSCATE_BLOCK_" . count($safeBlocks) . "__";
                    // Minify isi jadi 1 baris, tapi pertahankan fungsi
                    $content = $matches[0];
                    $content = preg_replace('/\s+/', ' ', $content);
                    $content = trim($content);
                    $safeBlocks[$key] = $content;
                    return $key;
                },
                $html
            );

            // 2. Hapus komentar HTML (kecuali conditional)
            $html = preg_replace('/<!--(?!\[if).*?-->/s', '', $html);

            // 3. Acak urutan atribut di semua tag
            $html = preg_replace_callback(
                '#<([a-zA-Z0-9:-]+)([^>]*)>#',
                function ($m) {
                    $tag = $m[1];
                    $attrs = $m[2];

                    if (empty(trim($attrs))) {
                        return "<$tag>";
                    }

                    // Ambil semua atribut: name="value"
                    preg_match_all('#\s+([a-zA-Z0-9:-]+)=(["\'])(.*?)\2#', $attrs, $matches, PREG_SET_ORDER);

                    $attrList = [];
                    foreach ($matches as $match) {
                        $attrList[] = $match[1] . '=' . $match[2] . $match[3] . $match[2];
                    }

                    // Acak urutan atribut
                    shuffle($attrList);

                    return '<' . $tag . ' ' . implode(' ', $attrList) . '>';
                },
                $html
            );

            // 4. Minify: hapus semua spasi, newline, tab → 1 baris
            $html = preg_replace('/\s+/', ' ', $html);
            $html = trim($html);

            // 5. Tambahkan spasi acak ringan antar tag (biar tidak terlalu padat)
            $html = preg_replace_callback('#>#', function () {
                return '>' . str_repeat(' ', mt_rand(0, 1));
            }, $html);

            // 6. Kembalikan blok aman (CSS/JS tetap 1 baris tapi utuh)
            foreach ($safeBlocks as $key => $block) {
                $html = str_replace($key, $block, $html);
            }

            // 7. Tambahkan noise kecil di akhir (opsional)
            $noise = substr(base64_encode(random_bytes(8)), 0, 10);
            $html .= "<!--obf:$noise-->";

            $response->setContent($html);
        }

        return $response;
    }
}
