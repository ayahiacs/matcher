<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Collection;

class PropertySearchProfileMatcherService
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
     * Search Profile is considered matching when:
     *   - At least one SearchProfile field is matching (strict or loose match)
     *   - No field is miss matching
     *   - Any amount of Search profile fields can be missing.
     *
     * @return Collection $matches
     */
    function getMatchesCollection()
    {
        $property = $this->property;
        
        $result = collect();

        $this->searchProfiles->each(function ($searchProfile) use ($property, &$result) {
            // TODO score calculator class
            if($property->propertyType !== $searchProfile->propertyType){
                return false;
            }

            $matchScore = [
                'searchProfileId' => $searchProfile->id,
                'score' => 0,
                'strictMatchesCount' => 0,
                'looseMatchesCount' => 0
            ];
            $searchProfile->searchFields->each(function ($value, $key) use ($property, &$matchScore) {
                $propertyFieldValue = $property->fields->get($key);
                if($propertyFieldValue && $propertyFieldValue){
                    if(is_array($value)){
                        if($propertyFieldValue >= $value[0] && $propertyFieldValue <= $value[1]){
                            $matchScore['score'] += 20;
                            $matchScore['strictMatchesCount'] ++;
                        } else {
                            $lowerBound = $value[0] == null? null: $value[0] - ($value[0] * .25);
                            $upperBound = $value[1] == null? null: $value[1] + ($value[1] * .25);
                            $lowerCondition = is_null($lowerBound)? true: $propertyFieldValue >= $lowerBound;
                            $upperCondition = is_null($upperBound)? true: $propertyFieldValue <= $upperBound;
                            if($lowerCondition && $upperCondition){
                                $matchScore['score'] += 10;
                                $matchScore['looseMatchesCount'] ++;
                            } else {
                                $matchScore['score'] = 0;
                                return false;
                            }
                        }
                    } else if (is_scalar($value) && $value == $propertyFieldValue){
                        $matchScore['score'] += 20;
                    } else {
                        $matchScore['score'] = 0;
                        return false;
                    }
                }
            });
            if($matchScore['score'] > 0){
                $result->push($matchScore);
            }
        });

        return $result->sortByDesc('score');
    }
}
