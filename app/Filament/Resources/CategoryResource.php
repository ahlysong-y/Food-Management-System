<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Categories';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ត្រូវប្រាកដថាមាន TextInput សម្រាប់ name ដូចខាងក្រោមនេះ
                Forms\Components\TextInput::make('name')
                    ->required() // បង្ខំឱ្យវាយបញ្ចូល ដាច់ខាតមិនឱ្យទទេ
                    ->maxLength(255)
                    ->label('ឈ្មោះប្រភេទមុខម្ហូប'),

                Forms\Components\Textarea::make('description')
                    ->label('ការពិពណ៌នាបន្ថែម')
                    ->rows(3),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->label('ឈ្មោះប្រភេទ'),
                Tables\Columns\TextColumn::make('description')->limit(50)->label('ការពិពណ៌នា'),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
