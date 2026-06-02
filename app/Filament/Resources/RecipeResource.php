<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecipeResource\Pages;
use App\Models\Recipe;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RecipeResource extends Resource
{
    protected static ?string $model = Recipe::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Recipes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('menu_item_id')
                    ->relationship('menuItem', 'name')
                    ->required()
                    ->label('Menu Item'),
                Forms\Components\Select::make('ingredient_id')
                    ->relationship('ingredient', 'name')
                    ->required()
                    ->label('Ingredient / Raw Material'),
                Forms\Components\TextInput::make('quantity_required')
                    ->numeric()
                    ->required()
                    ->placeholder('Example: 0.20 (means 200g or 0.2 units)')
                    ->label('Quantity Required'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('menuItem.name')->searchable()->label('Menu Item'),
                Tables\Columns\TextColumn::make('ingredient.name')->label('Ingredient'),
                Tables\Columns\TextColumn::make('quantity_required')->label('Quantity Used'),
                Tables\Columns\TextColumn::make('ingredient.unit')->label('Unit'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecipes::route('/'),
            'create' => Pages\CreateRecipe::route('/create'),
            'edit' => Pages\EditRecipe::route('/{record}/edit'),
        ];
    }
}
