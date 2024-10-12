<?php

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Workbench\Database\Factories\OrganizationFactory;

class Organization extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $connection = 'testing';

    protected $guarded = [];

    public static function newFactory(): OrganizationFactory
    {
        return OrganizationFactory::new();
    }
}
