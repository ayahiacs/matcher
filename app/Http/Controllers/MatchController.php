<?php

namespace App\Http\Controllers;

use App\Services\PropertySearchProfileMatcherService;
use App\Services\TestingDataGeneratorService;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    protected $properties;
    protected $searchProfiles;

    function __construct(TestingDataGeneratorService $testingDataGenerator)
    {
        $this->searchProfiles;
        $this->properties = $testingDataGenerator->generateProperties();
        $this->searchProfiles = $testingDataGenerator->generateSearchProfiles();
    }

    /**
     * List of matched search profile ids with score
     */
    function match($propertyId)
    {
        $property = $this->properties->first(function ($property, $key) use ($propertyId) {
            return $property->id == $propertyId;
        });

        if(!$property){
            return response()->json(['message' => 'Not found.'], 404);
        }

        $matcher = new PropertySearchProfileMatcherService($property, $this->searchProfiles);

        $matches = $matcher->getMatchesCollection();

        return response()->json($matches->values());
    }
}
