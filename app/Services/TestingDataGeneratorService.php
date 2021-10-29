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
                'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
                'searchFields' => collect([
                    "area" => [100, 200],
                    "yearOfConstruction" => "2011",
                ])
            ]),
            SearchProfile::factory()->make([
                'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111112',
                'searchFields' => collect([
                    "area" => "100",
                ])
            ]),
            SearchProfile::factory()->make([
                'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
                'searchFields' => collect([
                    "area" => "180",
                    "yearOfConstruction" => "2011",
                ])
            ]),
            SearchProfile::factory()->make([
                'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
                'searchFields' => collect([
                    "heatingType" => "gas",
                    "parking" => true,
                    "returnActual" => "12.8",
                ])
            ]),
            SearchProfile::factory()->make([
                'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
                'searchFields' => collect([
                    "area" => "180",
                    "yearOfConstruction" => "2011",
                    "heatingType" => "gas",
                    "parking" => true,
                    "returnActual" => "12.8",    
                ])
            ]),
            SearchProfile::factory()->make([
                'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
                'searchFields' => collect([
                    "area" => "180",
                    "yearOfConstruction" => "2011",
                    "parking" => true,
                    "returnActual" => "12.8",
                    "rent" => "3750"
                ])
            ]),
            SearchProfile::factory()->make([
                'propertyType' => '5d5922ce-4372-4e7d-9ffd-111111111111',
                'searchFields' => collect([
                    "area" => [50, 95],
                    "rent" => [4000, 5000]
                ])
            ])
        );
        
        return $searchProfiles;
    }
}