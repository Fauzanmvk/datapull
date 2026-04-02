<?php

namespace App\Filament\Resources\FormResponses;

use App\Filament\Resources\FormResponses\Pages\CreateFormResponse;
use App\Filament\Resources\FormResponses\Pages\EditFormResponse;
use App\Filament\Resources\FormResponses\Pages\ListFormResponses;
use App\Filament\Resources\FormResponses\Pages\ViewFormResponse;
use App\Filament\Resources\FormResponses\Schemas\FormResponseForm;
use App\Filament\Resources\FormResponses\Schemas\FormResponseInfolist;
use App\Filament\Resources\FormResponses\Tables\FormResponsesTable;
use App\Models\FormResponse;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FormResponseResource extends Resource
{
    protected static ?string $model = FormResponse::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'cagar_budaya';

    public static function form(Schema $schema): Schema
    {
        return FormResponseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FormResponseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FormResponsesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFormResponses::route('/'),
            'create' => CreateFormResponse::route('/create'),
            'view' => ViewFormResponse::route('/{record}'),
            'edit' => EditFormResponse::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
