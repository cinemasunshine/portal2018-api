<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Doctrine\Entities\ShowingFormat as ShowingFormatEntity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowingFormat extends JsonResource
{
    public function __construct(ShowingFormatEntity $showingFormat)
    {
        parent::__construct($showingFormat);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array{
     *     system_id: int,
     *     system_name: string,
     *     sound_id: int,
     *     sound_name: string,
     *     voice_id: int,
     *     voice_name: string,
     * }
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    public function toArray($request): array
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
