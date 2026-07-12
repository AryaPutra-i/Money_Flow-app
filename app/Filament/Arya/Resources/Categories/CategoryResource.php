<?php

namespace App\Filament\Arya\Resources\Categories;

use App\Filament\Arya\Resources\Categories\Pages\CreateCategory;
use App\Filament\Arya\Resources\Categories\Pages\EditCategory;
use App\Filament\Arya\Resources\Categories\Pages\ListCategories;
use App\Filament\Arya\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Arya\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Contracts\Support\Htmlable;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;
    
    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?string $recordTitleAttribute = 'name_category';

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->name_category;
    }

    public static function getGloballySearchableAttrributes(): array
    {
        return [
            'name_category',
            'workspace.name',
        ],
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Workspace' => $record->workspace->name,
            'Type' => $record->type_category,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
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
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
