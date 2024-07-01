<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VooResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'numero' => $this->numero,
            'aeroporto_origem' => new AeroportoResource($this->whenLoaded('aeroportoOrigem')),
            'aeroporto_destino' => new AeroportoResource($this->whenLoaded('aeroportoDestino')),
            'data_hora_partida' => $this->data_hora_partida,
        ];
    }
}
