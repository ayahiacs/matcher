<?php

namespace Tests\Unit;

use App\Models\Property;
use App\Models\SearchProfile;
use App\Services\MatchScoreService;
use App\Services\MatchService;
use Tests\TestCase;

class MatchServiceTest extends TestCase
{
    public function test_it_sorts_result_by_score_desc()
    {
        $property = Property::factory()->make([
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'fields' => collect([
                "area" => "100",
                "yearOfConstruction" => "2011",
                "rooms" => "5",
                "heatingType" => "gas",
                "parking" => true,
                "returnActual" => "12.8",
                "rent" => "3750"
            ])
        ]);
        $serchProfiles = collect([
            SearchProfile::factory()->make([
                'id' => '1',
                'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
                'searchFields' => collect([
                    "area" => [100, 200],
                    "rooms" => "5",
                    "yearOfConstruction" => "2011",
                ])
            ]),
            SearchProfile::factory()->make([
                'id' => '2',
                'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
                'searchFields' => collect([
                    "area" => [100, 200],
                    "yearOfConstruction" => "2011",
                ])
            ]),
            SearchProfile::factory()->make([
                'id' => '3',
                'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
                'searchFields' => collect([
                    "area" => [100, 200],
                    "yearOfConstruction" => "2011",
                    "parking" => true,
                    "returnActual" => "12.8",    
                ])
            ]),
        ]);

        $matchService = new MatchService($property, $serchProfiles);

        $matches = $matchService->getMatchesCollection();
        
        $this->assertEquals($matches->pluck('searchProfileId'), $matches->sortByDesc('score')->pluck('searchProfileId'));
    }
}
