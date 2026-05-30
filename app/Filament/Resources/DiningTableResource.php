<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiningTableResource\Pages;
use App\Models\DiningTable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DiningTableResource extends Resource
{
    protected static ?string $model = DiningTable::class;
    protected static ?string $navigationIcon = 'heroicon-o-table-cells';
    protected static ?string $navigationLabel = 'តុអាហារក្នុងហាង';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('ព័ត៌មានតុអាហារ')->schema([
                    // 🛠️ សំខាន់បំផុត៖ ត្រូវមានកន្លែងជ្រើសរើសសាខា (branch_id)
                    Forms\Components\Select::make('branch_id')
                        ->relationship('branch', 'name')
                        ->required()
                        ->label('ជ្រើសរើសសាខា'),

                    Forms\Components\TextInput::make('table_no')
                        ->required()
                        ->maxLength(255)
                        ->label('លេខតុ'),

                    Forms\Components\TextInput::make('capacity')
                        ->numeric()
                        ->required()
                        ->default(4)
                        ->label('ចំនួនកៅអី/ចំណុះផ្ទុក'),

                    Forms\Components\Select::make('status')
                        ->options([
                            'Available' => 'ទំនេរ (Available)',
                            'Reserved' => 'កក់ទុក (Reserved)',
                            'Occupied' => 'មានភ្ញៀវ (Occupied)',
                            'Cleaning' => 'កំពុងសម្អាត (Cleaning)',
                        ])
                        ->default('Available')
                        ->required()
                        ->label('ស្ថានភាពតុ'),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('table_no')->searchable()->label('លេខតុ'),
                Tables\Columns\TextColumn::make('branch.name')->label('សាខា'),
                Tables\Columns\TextColumn::make('capacity')->label('ចំនួនកៅអី'),
                Tables\Columns\TextColumn::make('status')->label('ស្ថានភាព'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDiningTables::route('/'),
            'create' => Pages\CreateDiningTable::route('/create'),
            'edit' => Pages\EditDiningTable::route('/{record}/edit'),
        ];
    }
}
