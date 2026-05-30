<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;
    protected static ?string $navigationIcon = 'heroicon-o-cake';
    protected static ?string $navigationLabel = 'Menu Items';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('ព័ត៌មានមុខម្ហូប')->schema([
                    // 🛠️ សំខាន់បំផុត៖ ត្រូវមានកន្លែងជ្រើសរើសប្រភេទមុខម្ហូប (category_id)
                    Forms\Components\Select::make('category_id')
                        ->relationship('category', 'name')
                        ->required()
                        ->label('ប្រភេទមុខម្ហូប'),

                    Forms\Components\TextInput::make('item_code')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('ឧទាហរណ៍៖ FD001')
                        ->label('កូដមុខម្ហូប'),

                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('ឧទាហរណ៍៖ ឡុកឡាក់សាច់គោ')
                        ->label('ឈ្មោះមុខម្ហូប'),

                    Forms\Components\TextInput::make('selling_price')
                        ->numeric()
                        ->prefix('$')
                        ->required()
                        ->placeholder('0.00')
                        ->label('តម្លៃលក់'),

                    Forms\Components\Textarea::make('description')
                        ->columnSpanFull()
                        ->label('ការពិពណ៌នាបន្ថែម'),

                    Forms\Components\FileUpload::make('image')
                        ->image()
                        ->directory('menu-items')
                        ->label('រូបភាពម្ហូប'),

                    Forms\Components\Toggle::make('status')
                        ->default(true)
                        ->label('ស្ថានភាពមានលក់'),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->circular()->label('រូបភាព'),
                Tables\Columns\TextColumn::make('item_code')->searchable()->label('កូដ'),
                Tables\Columns\TextColumn::make('name')->searchable()->label('ឈ្មោះមុខម្ហូប'),
                Tables\Columns\TextColumn::make('category.name')->label('ប្រភេទ'),
                Tables\Columns\TextColumn::make('selling_price')->money('USD')->label('តម្លៃលក់'),
                Tables\Columns\IconColumn::make('status')->boolean()->label('មានលក់'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
