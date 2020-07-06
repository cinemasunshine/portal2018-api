<?php

namespace App\Http\Resources;

use App\Doctrine\Entities\ShowingFormat as ShowingFormatEntity;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowingFormat extends JsonResource
{
    /**
     * @param ShowingFormatEntity $showingFormat
     */
    public function __construct(ShowingFormatEntity $showingFormat)
    {
        parent::__construct($showingFormat);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array{
     *     system: string,
     *     sound: string,
     *     voice: string,
     * }
     */
    public function toArray($request)
    {
        /** @var ShowingFormatEntity $showingFormat */
        $showingFormat = $this->resource;

        return [
            'system' => $showingFormat->getSystemText(),
            'sound' => $showingFormat->getSoundText(),
            'voice' => $showingFormat->getVoiceText(),
        ];
    }
}
