<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Collection;

class MatchService
{
    protected Property $property;
    protected Collection $searchProfiles;

    /**
     * @param Property $property
     * @param Collection $searchProfiles
     */
    function __construct(Property $property, Collection $searchProfiles)
    {
        $this->property = $property;
        $this->searchProfiles = $searchProfiles;
    }

    /**
     * @return Collection $matchScoreCollection
     */
    function getMatchesCollection()
    {
        $property = $this->property;
        
        $matchScoreCollection = collect();

        $this->searchProfiles->each(function ($searchProfile) use ($property, &$matchScoreCollection) {
            $matchScore = (new MatchScoreService($property, $searchProfile))->getMatchScore();
            if($matchScore['score'] > 0){
                $matchScoreCollection->push($matchScore);
            }
        });

        return $matchScoreCollection->sortByDesc('score');
    }
}
