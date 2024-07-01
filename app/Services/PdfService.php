<?php
namespace App\Services;

use App\Http\Controllers\Web\Controller;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{
    public function gerarPdf($index, $view, $dados, $tag){

        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new ImagickImageBackEnd()
        );

        $writer = new Writer($renderer);
        $qrTag = $writer->writeString($index);

        $filePath = storage_path("app/public/{$index}.png");
        file_put_contents($filePath, $qrTag);

        $pdf = Pdf::loadView($view, compact('dados', 'filePath'));

        // Faz o download do PDF
        return $pdf->download($tag.'.pdf');
    }
}