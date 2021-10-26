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
     * @return Collection $searchProfiles
     */
    function getMatchedSearchProfiles()
    {
        $property = $this->property;
        
        $filtered = $this->searchProfiles->filter(function ($searchProfile) use ($property) {
            if($property->propertyType !== $searchProfile->propertyType){
                return false;
            }

            $isMatch = true;
            $searchProfile->searchFields->each(function ($value, $key) use ($property, &$isMatch) {
                $propertyFieldValue = $property->fields->get($key);
                if($propertyFieldValue && $propertyFieldValue){
                    if(is_array($value)){
                        $lowerBound = $value[0] == null? null: $value[0] - ($value[0] * .25);
                        $upperBound = $value[1] == null? null: $value[1] + ($value[1] * .25);
                        $lowerCondition = is_null($lowerBound)? true: $propertyFieldValue >= $lowerBound;
                        $upperCondition = is_null($upperBound)? true: $propertyFieldValue <= $upperBound;
                        $isMatch = $lowerCondition && $upperCondition;
                    } else if (is_scalar($value)){
                        $isMatch = $value == $propertyFieldValue;
                    }
                    return $isMatch;
                }
            });
            return $isMatch;
        });

        return $filtered;
    }
}
