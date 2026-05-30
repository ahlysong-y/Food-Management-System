<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationIcon = 'heroicon-o-users'; // បង្ហាញ Icon លើ Menu
    protected static ?string $navigationLabel = 'Employees'; // ប្ដូរឈ្មោះ Menu ទៅជា "Employees"

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required()
                    ->label('សាខា'),
                Forms\Components\Select::make('role_id')
                    ->relationship('role', 'name')
                    ->required()
                    ->label('តួនាទី'),
                Forms\Components\TextInput::make('employee_code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('កូដបុគ្គលិក'),
                Forms\Components\TextInput::make('fullname')
                    ->required()
                    ->label('ឈ្មោះពេញ'),
                Forms\Components\Select::make('gender')
                    ->options([
                        'Male' => 'ប្រុស',
                        'Female' => 'ស្រី',
                    ])
                    ->required()
                    ->label('ភេទ'),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->label('លេខទូរស័ព្ទ'),
                Forms\Components\TextInput::make('salary')
                    ->numeric()
                    ->prefix('$')
                    ->label('ប្រាក់ខែ'),
                Forms\Components\Toggle::make('status')
                    ->default(true)
                    ->label('ស្ថានភាពការងារ'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_code')->searchable()->label('កូដ'),
                Tables\Columns\TextColumn::make('fullname')->searchable()->label('ឈ្មោះ'),
                Tables\Columns\TextColumn::make('branch.name')->label('សាខា'),
                Tables\Columns\TextColumn::make('role.name')->label('តួនាទី'),
                Tables\Columns\TextColumn::make('salary')->money('USD')->label('ប្រាក់ខែ'),
                Tables\Columns\IconColumn::make('status')->boolean()->label('សកម្ម'),
            ])
            ->filters([
                // អាចបន្ថែម Filter តាមសាខា ឬតួនាទីនៅទីនេះ
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
