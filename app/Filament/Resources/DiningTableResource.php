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
    protected static ?string $navigationLabel = 'Dining Tables';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dining Table Information')->schema([
                    // 🛠️ Important: Must include branch selection (branch_id)
                    Forms\Components\Select::make('branch_id')
                        ->relationship('branch', 'name')
                        ->required()
                        ->label('Select Branch'),

                    Forms\Components\TextInput::make('table_no')
                        ->required()
                        ->maxLength(255)
                        ->label('Table Number'),

                    Forms\Components\TextInput::make('capacity')
                        ->numeric()
                        ->required()
                        ->default(4)
                        ->label('Capacity / Seats'),

                    Forms\Components\Select::make('status')
                        ->options([
                            'Available' => 'Available',
                            'Reserved' => 'Reserved',
                            'Occupied' => 'Occupied',
                            'Cleaning' => 'Cleaning',
                        ])
                        ->default('Available')
                        ->required()
                        ->label('Table Status'),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('table_no')->searchable()->label('Table Number'),
                Tables\Columns\TextColumn::make('branch.name')->label('Branch'),
                Tables\Columns\TextColumn::make('capacity')->label('Capacity'),
                Tables\Columns\TextColumn::make('status')->label('Status'),
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
