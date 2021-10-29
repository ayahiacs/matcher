<?php

namespace App\Http\Controllers;

use App\Services\MatchService;
use App\Services\TestingDataGeneratorService;

class MatchController extends Controller
{
    protected $properties;
    protected $searchProfiles;

    function __construct(TestingDataGeneratorService $testingDataGenerator)
    {
        // generate some data for POC
        $this->properties = $testingDataGenerator->generateProperties();
        $this->searchProfiles = $testingDataGenerator->generateSearchProfiles();
    }

    /**
     * List of matched search profile ids with score, strict and loose match count
     */
    function match($propertyId)
    {
        $property = $this->properties->first(function ($property, $key) use ($propertyId) {
            return $property->id == $propertyId;
        });

        if(!$property){
            return response()->json(['message' => 'Not found.'], 404);
        }

        $matcher = new MatchService($property, $this->searchProfiles);

        $matches = $matcher->getMatchesCollection();

        return response()->json(['data' => $matches->values()]);
    }
}
