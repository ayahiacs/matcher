<?php

namespace Tests\Unit;

use App\Models\Property;
use App\Models\SearchProfile;
use App\Services\MatchScoreService;
use Tests\TestCase;

class MatchScoreServiceTest extends TestCase
{
    public function test_different_property_type_should_miss()
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
        $serchProfile = SearchProfile::factory()->make([
            'id' => '1',
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111112',
            'searchFields' => collect([
                "area" => [100, 200],
                "yearOfConstruction" => "2011",
            ])
        ]);
        $matchScoreService = new MatchScoreService($property, $serchProfile);

        $matchScore = $matchScoreService->getMatchScore();
        $this->assertEqualsCanonicalizing([
            'searchProfileId' => 1,
            'score' => 0,
            'strictMatchesCount' => 0,
            'looseMatchesCount' => 0
        ], $matchScore);
    }

    public function test_strict_match_hit()
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
        $serchProfile = SearchProfile::factory()->make([
            'id' => '1',
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'searchFields' => collect([
                "area" => "100",
                "yearOfConstruction" => "2011",
                "rooms" => "5",
                "heatingType" => "gas",
                "parking" => true,
                "returnActual" => "12.8",
                "rent" => "3750"
            ])
        ]);
        $matchScoreService = new MatchScoreService($property, $serchProfile);

        $matchScore = $matchScoreService->getMatchScore();
        $this->assertEqualsCanonicalizing([
            'searchProfileId' => 1,
            'score' => 140,
            'strictMatchesCount' => 7,
            'looseMatchesCount' => 0
        ], $matchScore);
    }

    public function test_strict_match_miss()
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
        $serchProfile = SearchProfile::factory()->make([
            'id' => '1',
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'searchFields' => collect([
                "area" => "100",
                "yearOfConstruction" => "2011",
                "rooms" => "5",
                "heatingType" => "gas",
                "parking" => true,
                "returnActual" => "12.8",
                "rent" => "3751"
            ])
        ]);
        $matchScoreService = new MatchScoreService($property, $serchProfile);

        $matchScore = $matchScoreService->getMatchScore();
        $this->assertEquals(0, $matchScore['score']);
    }

    public function test_loose_match_lower_bound_within_range_should_hit()
    {
        $property = Property::factory()->make([
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'fields' => collect([
                "area" => "75",
                "yearOfConstruction" => "2011",
                "rooms" => "5",
                "heatingType" => "gas",
                "parking" => true,
                "returnActual" => "12.8",
                "rent" => "3750"
            ])
        ]);
        $serchProfile = SearchProfile::factory()->make([
            'id' => '1',
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'searchFields' => collect([
                "area" => [100, 200],
                "yearOfConstruction" => "2011",
            ])
        ]);
        $matchScoreService = new MatchScoreService($property, $serchProfile);

        $matchScore = $matchScoreService->getMatchScore();
        $this->assertEqualsCanonicalizing([
            'searchProfileId' => 1,
            'score' => 30,
            'strictMatchesCount' => 1,
            'looseMatchesCount' => 1
        ], $matchScore);
    }

    public function test_loose_match_upper_bound_within_range_should_hit()
    {
        $property = Property::factory()->make([
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'fields' => collect([
                "area" => "250",
                "yearOfConstruction" => "2011",
                "rooms" => "5",
                "heatingType" => "gas",
                "parking" => true,
                "returnActual" => "12.8",
                "rent" => "3750"
            ])
        ]);
        $serchProfile = SearchProfile::factory()->make([
            'id' => '1',
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'searchFields' => collect([
                "area" => [100, 200],
                "yearOfConstruction" => "2011",
            ])
        ]);
        $matchScoreService = new MatchScoreService($property, $serchProfile);

        $matchScore = $matchScoreService->getMatchScore();
        $this->assertEqualsCanonicalizing([
            'searchProfileId' => 1,
            'score' => 30,
            'strictMatchesCount' => 1,
            'looseMatchesCount' => 1
        ], $matchScore);
    }

    public function test_loose_match_lower_bound_out_of_range_should_miss()
    {
        $property = Property::factory()->make([
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'fields' => collect([
                "area" => "74",
                "yearOfConstruction" => "2011",
                "rooms" => "5",
                "heatingType" => "gas",
                "parking" => true,
                "returnActual" => "12.8",
                "rent" => "3750"
            ])
        ]);
        $serchProfile = SearchProfile::factory()->make([
            'id' => '1',
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'searchFields' => collect([
                "area" => [100, 200],
            ])
        ]);
        $matchScoreService = new MatchScoreService($property, $serchProfile);

        $matchScore = $matchScoreService->getMatchScore();
        $this->assertEqualsCanonicalizing([
            'searchProfileId' => 1,
            'score' => 0,
            'strictMatchesCount' => 0,
            'looseMatchesCount' => 0
        ], $matchScore);
    }

    public function test_loose_match_upper_bound_out_of_range_should_miss()
    {
        $property = Property::factory()->make([
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'fields' => collect([
                "area" => "251",
                "yearOfConstruction" => "2011",
                "rooms" => "5",
                "heatingType" => "gas",
                "parking" => true,
                "returnActual" => "12.8",
                "rent" => "3750"
            ])
        ]);
        $serchProfile = SearchProfile::factory()->make([
            'id' => '1',
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'searchFields' => collect([
                "area" => [100, 200],
                "yearOfConstruction" => "2011",
            ])
        ]);
        $matchScoreService = new MatchScoreService($property, $serchProfile);

        $matchScore = $matchScoreService->getMatchScore();
        $this->assertEqualsCanonicalizing([
            'searchProfileId' => 1,
            'score' => 0,
            'strictMatchesCount' => 0,
            'looseMatchesCount' => 0
        ], $matchScore);
    }

    public function test_range_match_null_upper_bound()
    {
        $property = Property::factory()->make([
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'fields' => collect([
                "area" => "1000",
                "yearOfConstruction" => "2011",
                "rooms" => "5",
                "heatingType" => "gas",
                "parking" => true,
                "returnActual" => "12.8",
                "rent" => "3750"
            ])
        ]);
        $serchProfile = SearchProfile::factory()->make([
            'id' => '1',
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'searchFields' => collect([
                "area" => [100, null],
            ])
        ]);
        $matchScoreService = new MatchScoreService($property, $serchProfile);

        $matchScore = $matchScoreService->getMatchScore();
        $this->assertEqualsCanonicalizing([
            'searchProfileId' => 1,
            'score' => 20,
            'strictMatchesCount' => 1,
            'looseMatchesCount' => 0
        ], $matchScore);
    }

    public function test_range_match_null_lower_bound()
    {
        $property = Property::factory()->make([
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'fields' => collect([
                "area" => "251",
                "yearOfConstruction" => "2011",
                "rooms" => "5",
                "heatingType" => "gas",
                "parking" => true,
                "returnActual" => "12.8",
                "rent" => "3750"
            ])
        ]);
        $serchProfile = SearchProfile::factory()->make([
            'id' => '1',
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'searchFields' => collect([
                "area" => [null, 500],
            ])
        ]);
        $matchScoreService = new MatchScoreService($property, $serchProfile);

        $matchScore = $matchScoreService->getMatchScore();
        $this->assertEqualsCanonicalizing([
            'searchProfileId' => 1,
            'score' => 20,
            'strictMatchesCount' => 1,
            'looseMatchesCount' => 0
        ], $matchScore);
    }

    public function test_match_boolean_field_hit()
    {
        $property = Property::factory()->make([
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'fields' => collect([
                "area" => "251",
                "yearOfConstruction" => "2011",
                "rooms" => "5",
                "heatingType" => "gas",
                "parking" => true,
                "elevator" => false,
                "returnActual" => "12.8",
                "rent" => "3750"
            ])
        ]);
        $serchProfile = SearchProfile::factory()->make([
            'id' => '1',
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'searchFields' => collect([
                "parking" => true,
                "elevator" => false
            ])
        ]);
        $matchScoreService = new MatchScoreService($property, $serchProfile);

        $matchScore = $matchScoreService->getMatchScore();
        $this->assertEqualsCanonicalizing([
            'searchProfileId' => 1,
            'score' => 40,
            'strictMatchesCount' => 2,
            'looseMatchesCount' => 0,
        ], $matchScore);
    }

    public function test_match_boolean_field_miss()
    {
        $property = Property::factory()->make([
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'fields' => collect([
                "area" => "251",
                "yearOfConstruction" => "2011",
                "rooms" => "5",
                "heatingType" => "gas",
                "parking" => true,
                "elevator" => false,
                "returnActual" => "12.8",
                "rent" => "3750"
            ])
        ]);
        $serchProfile = SearchProfile::factory()->make([
            'id' => '1',
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'searchFields' => collect([
                "parking" => false,
                "elevator" => true
            ])
        ]);
        $matchScoreService = new MatchScoreService($property, $serchProfile);

        $matchScore = $matchScoreService->getMatchScore();
        $this->assertEqualsCanonicalizing([
            'searchProfileId' => 1,
            'score' => 0,
            'strictMatchesCount' => 0,
            'looseMatchesCount' => 0,
        ], $matchScore);
    }

    public function test_search_profile_not_exist_field_should_miss()
    {
        $property = Property::factory()->make([
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'fields' => collect([
                "area" => "200",
                "yearOfConstruction" => "2011",
                "rooms" => "5",
                "heatingType" => "gas",
                "parking" => true,
                "returnActual" => "12.8",
                "rent" => "3750"
            ])
        ]);
        $serchProfile = SearchProfile::factory()->make([
            'id' => '1',
            'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
            'searchFields' => collect([
                "area" => "200",
                "yearOfConstruction" => "2011",
                "rooms" => "5",
                "heatingType" => "gas",
                "parking" => true,
                "returnActual" => "12.8",
                "rent" => "3750",
                "notExistField" => 'any',
            ])
        ]);
        $matchScoreService = new MatchScoreService($property, $serchProfile);

        $matchScore = $matchScoreService->getMatchScore();
        $this->assertEquals(0, $matchScore['score']);
    }

}
