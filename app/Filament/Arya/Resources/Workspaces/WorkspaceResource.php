<?php

namespace App\Filament\Arya\Resources\Workspaces;

use App\Filament\Arya\Resources\Workspaces\Pages\CreateWorkspace;
use App\Filament\Arya\Resources\Workspaces\Pages\EditWorkspace;
use App\Filament\Arya\Resources\Workspaces\Pages\ListWorkspaces;
use App\Filament\Arya\Resources\Workspaces\Schemas\WorkspaceForm;
use App\Filament\Arya\Resources\Workspaces\Tables\WorkspacesTable;
use App\Models\Workspace;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WorkspaceResource extends Resource
{
    protected static ?string $model = Workspace::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;
    
    protected static string | UnitEnum | null $navigationGroup = 'Workspace Isolation';

    protected static ?string $recordTitleAttribute = 'Workspace';

    public static function form(Schema $schema): Schema
    {
        return WorkspaceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkspacesTable::configure($table);
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
            'index' => ListWorkspaces::route('/'),
            'create' => CreateWorkspace::route('/create'),
            'edit' => EditWorkspace::route('/{record}/edit'),
        ];
    }
}
