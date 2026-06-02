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
    protected static ?string $navigationIcon = 'heroicon-o-users'; // Show Icon on Menu
    protected static ?string $navigationLabel = 'Employees'; // Change Menu Name to "Employees"

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required()
                    ->label('Branch'),
                Forms\Components\Select::make('role_id')
                    ->relationship('role', 'name')
                    ->required()
                    ->label('Role'),
                Forms\Components\TextInput::make('employee_code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Employee Code'),
                Forms\Components\TextInput::make('fullname')
                    ->required()
                    ->label('Full Name'),
                Forms\Components\Select::make('gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                    ])
                    ->required()
                    ->label('Gender'),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->label('Phone Number'),
                Forms\Components\TextInput::make('salary')
                    ->numeric()
                    ->prefix('$')
                    ->label('Salary'),
                Forms\Components\Toggle::make('status')
                    ->default(true)
                    ->label('Employment Status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_code')->searchable()->label('Code'),
                Tables\Columns\TextColumn::make('fullname')->searchable()->label('Name'),
                Tables\Columns\TextColumn::make('branch.name')->label('Branch'),
                Tables\Columns\TextColumn::make('role.name')->label('Role'),
                Tables\Columns\TextColumn::make('salary')->money('USD')->label('Salary'),
                Tables\Columns\IconColumn::make('status')->boolean()->label('Active'),
            ])
            ->filters([
                // Can add filters by branch or role here
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
