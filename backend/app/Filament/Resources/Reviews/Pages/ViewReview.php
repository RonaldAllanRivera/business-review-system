<?php

namespace App\Filament\Resources\Reviews\Pages;

use App\Filament\Resources\Reviews\ReviewResource;
use App\Filament\Resources\Reviews\Schemas\ReviewInfolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewReview extends ViewRecord
{
    protected static string $resource = ReviewResource::class;

    public function infolist(Schema $schema): Schema
    {
        return ReviewInfolist::configure($schema);
    }
}
