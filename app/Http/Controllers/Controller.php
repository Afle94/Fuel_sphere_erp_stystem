<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    protected function shouldStreamRawPdf(Request $request): bool
    {
        return $request->boolean('raw');
    }

    protected function pdfViewer(Request $request, string $title)
    {
        $query = array_merge($request->query(), ['raw' => 1]);
        $pdfUrl = $request->url() . '?' . http_build_query($query);

        return response()->view('pdf_viewer', compact('title', 'pdfUrl'));
    }
}
