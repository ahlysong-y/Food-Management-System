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
    protected static ?string $navigationLabel = 'Raw materials in stock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ingredient_code')->required()->label('កូដគ្រឿងផ្សំ'),
                Forms\Components\TextInput::make('name')->required()->label('ឈ្មោះគ្រឿងផ្សំ'),
                Forms\Components\TextInput::make('unit')->placeholder('គីឡូក្រាម, លីត្រ, កញ្ចប់...')->required()->label('ឯកតារាប់'),
                Forms\Components\TextInput::make('current_stock')->numeric()->default(0)->label('ស្តុកបច្ចុប្បន្ន'),
                Forms\Components\TextInput::make('minimum_stock')->numeric()->default(5)->label('ស្តុកទាបបំផុត (ត្រូវទិញថែម)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ingredient_code')->label('កូដ'),
                Tables\Columns\TextColumn::make('name')->searchable()->label('ឈ្មោះ'),
                Tables\Columns\TextColumn::make('current_stock')->label('ស្តុកបច្ចុប្បន្ន'),
                Tables\Columns\TextColumn::make('unit')->label('ឯកតា'),

                // ពណ៌នាសម្គាល់៖ បើស្តុកបច្ចុប្បន្នទាបជាងស្តុកអប្បបរមា វានឹងចេញពណ៌ក្រហមព្រមានភ្លាម
                Tables\Columns\TextColumn::make('minimum_stock')
                    ->color(fn($record) => $record->current_stock <= $record->minimum_stock ? 'danger' : 'success')
                    ->label('ស្តុកកំណត់ប្រកាសអាសន្ន'),
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
