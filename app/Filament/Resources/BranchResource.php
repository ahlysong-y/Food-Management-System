<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchResource\Pages;
use App\Models\Branch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront'; // ប្តូរ Icon ឱ្យស្អាតសមជាសាខាហាង
    protected static ?string $navigationLabel = 'Branch Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Branch Information')->schema([
                    // 🛠️ សំខាន់បំផុត៖ ត្រូវមាន TextInput សម្រាប់ code និង name ដូចខាងក្រោមនេះ
                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Example: B001')
                        ->label('Branch Code'),

                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Example: Phnom Penh Branch')
                        ->label('Branch Name'),

                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->label('Phone Number'),

                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->label('Email Address'),

                    Forms\Components\Textarea::make('address')
                        ->columnSpanFull()
                        ->label('Address'),

                    Forms\Components\Toggle::make('status')
                        ->default(true)
                        ->label('Status'),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->searchable()->label('Branch Code'),
                Tables\Columns\TextColumn::make('name')->searchable()->label('Branch Name'),
                Tables\Columns\TextColumn::make('phone')->label('Phone Number'),
                Tables\Columns\IconColumn::make('status')->boolean()->label('Status'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Created At'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBranches::route('/'),
            'create' => Pages\CreateBranch::route('/create'),
            'edit' => Pages\EditBranch::route('/{record}/edit'),
        ];
    }
}
