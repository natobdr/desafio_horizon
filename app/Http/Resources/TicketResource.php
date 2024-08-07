<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'classe_id' => new ClasseResource($this->whenLoaded('classe')),
            'passageiro_id' => new PassageiroResource($this->whenLoaded('passageiro')),
            'voo_id' => new VooResource($this->whenLoaded('voo')),
            'preco_total' => $this->preco_total,
            'ticket_status' => $this->ticket_status,
        ];
    }
}
