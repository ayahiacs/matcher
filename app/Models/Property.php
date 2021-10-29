<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;
    
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'address',
        'propertyType',
        'fields'
    ];

    private string $name;
    private string $address;
    private string $propertyType;
    private array $fields;

    public function getPropertyType()
    {
        return $this->propertyType;
    }

    public function getPropertyFields()
    {
        return $this->fields;
    }
}