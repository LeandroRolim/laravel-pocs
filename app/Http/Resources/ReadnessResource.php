<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use function Pest\Laravel\artisan;

class ReadnessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *z
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => 'ok',
        ];
    }
}
