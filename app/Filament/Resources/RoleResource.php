<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group'; // Icon ក្រុមការងារ/តួនាទី
    protected static ?string $navigationLabel = 'Roles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('ព័ត៌មានតួនាទីបុគ្គលិក')->schema([
                    // 🛠️ សំខាន់បំផុត៖ ត្រូវមាន TextInput សម្រាប់ name
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('ឧទាហរណ៍៖ Cashier, Chef, Waiter')
                        ->label('ឈ្មោះតួនាទី'),

                    // Forms\Components\Textarea::make('description')
                    //     ->label('ការពិពណ៌នាពីភារកិច្ច')
                    //     ->rows(3),
                ])->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->label('ឈ្មោះតួនាទី'),
                // Tables\Columns\TextColumn::make('description')->limit(50)->label('ការពិពណ៌នា'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('ថ្ងៃបង្កើត'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => RoleResource\Pages\ListRoles::route('/'),
            'create' => RoleResource\Pages\CreateRole::route('/create'),
            'edit' => RoleResource\Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
