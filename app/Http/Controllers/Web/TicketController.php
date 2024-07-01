<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\PdfService;
use BaconQrCode\Renderer\Image\PngImageBackEnd;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function downloadTag($id)
    {
        $ticket = Ticket::with('passageiro', 'bagagem')->findOrFail($id);
        $bagagem = $ticket->bagagem;
        $passageiro = $ticket->passageiro;

        return $this->pdfService->gerarPdf($bagagem->id, 'tag', [$bagagem, $passageiro], 'etiqueta');
    }

    public function downloadVoucher($id)
    {
        $ticket = Ticket::with('voo.aeroportoOrigem', 'voo.aeroportoDestino', 'passageiro', 'bagagem', 'classe.tipoClasse')->findOrFail($id);
        $passageiro = $ticket->passageiro;
        $classe = $ticket->classe;

        return $this->pdfService->gerarPdf($ticket->id, 'voucher', [$ticket, $passageiro, $classe], 'voucher');
    }
}
