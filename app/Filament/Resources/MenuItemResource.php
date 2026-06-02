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
                Forms\Components\Section::make('Menu Item Information')->schema([
                    // 🛠️ Important: Must include menu item category selection (category_id)
                    Forms\Components\Select::make('category_id')
                        ->relationship('category', 'name')
                        ->required()
                        ->label('Category'),

                    Forms\Components\TextInput::make('item_code')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Example: FD001')
                        ->label('Item Code'),

                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Example: Beef Lok Lak')
                        ->label('Item Name'),

                    Forms\Components\TextInput::make('selling_price')
                        ->numeric()
                        ->prefix('$')
                        ->required()
                        ->placeholder('0.00')
                        ->label('Selling Price'),

                    Forms\Components\Textarea::make('description')
                        ->columnSpanFull()
                        ->label('Description'),

                    Forms\Components\FileUpload::make('image')
                        ->image()
                        ->directory('menu-items')
                        ->label('Item Image'),

                    Forms\Components\Toggle::make('status')
                        ->default(true)
                        ->label('Available for Sale'),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->circular()->label('Image'),
                Tables\Columns\TextColumn::make('item_code')->searchable()->label('Code'),
                Tables\Columns\TextColumn::make('name')->searchable()->label('Item Name'),
                Tables\Columns\TextColumn::make('category.name')->label('Category'),
                Tables\Columns\TextColumn::make('selling_price')->money('USD')->label('Selling Price'),
                Tables\Columns\IconColumn::make('status')->boolean()->label('Available'),
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
