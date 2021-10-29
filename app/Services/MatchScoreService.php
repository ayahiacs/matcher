<?php

namespace App\Services;

use App\Models\Property;
use App\Models\SearchProfile;

class MatchScoreService
{
    /**
     * a property to be matched with score profile.
     *
     * @var Property
     */
    protected $property;

    /**
     * a search profile to be matched with property.
     *
     * @var Property
     */
    protected $searchProfile;

    /**
     * how many points should be added to score in case of strict match
     *
     * @var integer
     */
    protected $strictMatchPoints;

    /**
     * how many points should be added to score in case of loose match
     *
     * @var integer
     */
    protected $looseMatchPoints;

    /**
     * a factor of bounds if searchField has range
     * example if factore is .25 and range is [100,200] loose match should go for [75, 250]
     *
     * @var float 
     */
    protected $looseMatchFactor;

    function __construct(Property $property, SearchProfile $searchProfile)
    {
        $this->property = $property;
        $this->searchProfile = $searchProfile;
        $this->strictMatchPoints = config('match.score.strict_match_points');
        $this->looseMatchPoints = config('match.score.loose_match_points');
        $this->looseMatchFactor = config('match.score.loose_match_factor');
    }

    /**
     * returns array contains searchProfileId, strict matches count, loose matches count and score
     *
     * @return Array $matchScore
     */
    function getMatchScore()
    {
        $matchScore = [
            'searchProfileId' => $this->searchProfile->id,
            'score' => 0,
            'strictMatchesCount' => 0,
            'looseMatchesCount' => 0
        ];

        if (!$this->hasTheSamePropertyType($this->property, $this->searchProfile)) {
            return $matchScore;
        }

        foreach ($this->searchProfile->searchFields as $key => $searchField) {
            $propertyFieldValue = $this->property->fields->get($key);
            if (is_null($propertyFieldValue)) {
                $matchScore['score'] = 0;
                break;
            }
            if (is_array($searchField)) {
                $this->calculateRangePoints($propertyFieldValue, $searchField, $matchScore);
                if ($matchScore['score'] == 0) {
                    break;
                }
            } else if (is_scalar($searchField) && $searchField == $propertyFieldValue) {
                $matchScore['score'] += $this->strictMatchPoints;
                $matchScore['strictMatchesCount']++;
            } else {
                $matchScore['score'] = 0;
                break;
            }
        }

        return $matchScore;
    }

    protected function hasTheSamePropertyType($property, $searchProfile)
    {
        return $property->propertyType == $searchProfile->propertyType;
    }

    /**
     * Given property field and of type range search profile search field
     * it modifies the score and the strictMatchCount of a given matchScore object
     * @param scalar $propertyFieldValue
     * @param array $searchField
     * @param array $matchScore
     * @return void
     */
    protected function calculateRangePoints($propertyFieldValue, array $searchField, &$matchScore)
    {
        if ($propertyFieldValue >= $searchField[0] && $propertyFieldValue <= $searchField[1]) {
            $matchScore['score'] += $this->strictMatchPoints;
            $matchScore['strictMatchesCount']++;
        } else {
            $lowerBound = $searchField[0] == null ? null : $searchField[0] - ($searchField[0] * $this->looseMatchFactor);
            $upperBound = $searchField[1] == null ? null : $searchField[1] + ($searchField[1] * $this->looseMatchFactor);
            $lowerCondition = is_null($lowerBound) ? true : $propertyFieldValue >= $lowerBound;
            $upperCondition = is_null($upperBound) ? true : $propertyFieldValue <= $upperBound;
            if ($lowerCondition && $upperCondition) {
                $matchScore['score'] += $this->looseMatchPoints;
                $matchScore['looseMatchesCount']++;
            } else {
                $matchScore['score'] = 0;
            }
        }
    }
}
