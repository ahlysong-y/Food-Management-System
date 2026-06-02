<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IngredientResource\Pages;
use App\Models\Ingredient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IngredientResource extends Resource
{
    protected static ?string $model = Ingredient::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Raw Materials In Stock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ingredient_code')->required()->label('Ingredient Code'),
                Forms\Components\TextInput::make('name')->required()->label('Ingredient Name'),
                Forms\Components\TextInput::make('unit')->placeholder('kg, liters, packs...')->required()->label('Unit of Measure'),
                Forms\Components\TextInput::make('current_stock')->numeric()->default(0)->label('Current Stock'),
                Forms\Components\TextInput::make('minimum_stock')->numeric()->default(5)->label('Minimum Stock (Reorder Alert)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ingredient_code')->label('Code'),
                Tables\Columns\TextColumn::make('name')->searchable()->label('Name'),
                Tables\Columns\TextColumn::make('current_stock')->label('Current Stock'),
                Tables\Columns\TextColumn::make('unit')->label('Unit'),

                // Warning indicator: If current stock is less than or equal to minimum stock, it highlights in danger color (red)
                Tables\Columns\TextColumn::make('minimum_stock')
                    ->color(fn($record) => $record->current_stock <= $record->minimum_stock ? 'danger' : 'success')
                    ->label('Alert Level Stock'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIngredients::route('/'),
            'create' => Pages\CreateIngredient::route('/create'),
            'edit' => Pages\EditIngredient::route('/{record}/edit'),
        ];
    }
}
