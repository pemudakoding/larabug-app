<?php namespace App\Providers;

use App\Filament\Widgets\TotalUsers;
use Illuminate\Support\ServiceProvider;
use App\Filament\Widgets\TotalProjects;
use App\Filament\Widgets\ActiveProjects;
use App\Filament\Widgets\TotalExceptions;

class FilamentServiceProvider extends ServiceProvider
{
    protected $widgets = [
        TotalExceptions::class,
        TotalUsers::class,
        TotalProjects::class,
        ActiveProjects::class,
    ];
}