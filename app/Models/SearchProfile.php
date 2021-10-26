<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchProfile extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'propertyType',
        'searchFields'
    ];

    private string $name;
    private string $propertyType;
    private array $searchFields;

    public function getSearchProfilePropertyType()
    {
        return $this->propertyType;
    }

    public function getSearchProfileFields()
    {
        return $this->searchFields;
    }
}
