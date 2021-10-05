<?php

namespace App\Http\Controllers\Api;

use App\Models\Sponsor;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SponsorResource;

class SponsorController extends Controller
{
    public function index()
    {
        return SponsorResource::collection(Sponsor::all());
    }
}
