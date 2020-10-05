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
     * @param  \Illuminate\Http\Request $request
     * @return array{
     *     system_id: int,
     *     system_name: string,
     *     sound_id: int,
     *     sound_name: string,
     *     voice_id: int,
     *     voice_name: string,
     * }
     */
    public function toArray($request)
    {
        /** @var ShowingFormatEntity $showingFormat */
        $showingFormat = $this->resource;

        return [
            'system_id' => $showingFormat->getSystem(),
            'system_name' => $showingFormat->getSystemText(),
            'sound_id' => $showingFormat->getSound(),
            'sound_name' => $showingFormat->getSoundText(),
            'voice_id' => $showingFormat->getVoice(),
            'voice_name' => $showingFormat->getVoiceText(),
        ];
    }
}
