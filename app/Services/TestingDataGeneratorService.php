<?php

namespace App\Services;

use App\Models\Property;
use App\Models\SearchProfile;

class TestingDataGeneratorService
{
    /**
     * Generates a collection of Property objects
     *
     * @return \Illuminate\Support\Collection
     */
    function generateProperties()
    {
        $properties = collect();

        $properties->push(Property::factory()->make([
            'id' => 1,
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
        ]));

        return $properties;
    }

    /**
     * Generates a collections of searchProfile objects
     *
     * @return \Illuminate\Support\Collection
     */
    public function generateSearchProfiles()
    {
        $searchProfiles = collect();

        $searchProfiles->push(
            SearchProfile::factory()->make([
                'id' => 1,
                'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
                'searchFields' => collect([
                    "area" => [100, 200],
                    "yearOfConstruction" => "2011",
                ])
            ]),
            SearchProfile::factory()->make([
                'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
                'searchFields' => collect()
            ]),
            SearchProfile::factory()->make([
                'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
                'searchFields' => collect([
                    "area" => "180",
                    "yearOfConstruction" => "2011",
                ])
            ]),
            SearchProfile::factory()->make([
                'propertyType' => '5d5922ce-4372-4e7d-9ffd-222222222222',
                'searchFields' => collect([
                    "area" => "180",
                    "yearOfConstruction" => "2011",
                ])
            ]),
            SearchProfile::factory()->make([
                'propertyType' => '5d5922ce-4372-4e7d-9ffd-222222222222',
                'searchFields' => collect([
                    "area" => "180",
                    "yearOfConstruction" => "2011",
                ])
            ]),
            SearchProfile::factory()->make([
                'propertyType' => '5d5922ce-4372-4e7d-9ffd-222222222222',
                'searchFields' => collect([
                    "area" => "180",
                    "yearOfConstruction" => "2011",
                ])
            ]),
            SearchProfile::factory()->make([
                'propertyType' => '5d5922ce-4372-4e7d-9ffd-333333333333',
                'searchFields' => collect([
                    "area" => "180",
                    "yearOfConstruction" => "2011",
                ])
            ])
        );

        return $searchProfiles;
    }
}