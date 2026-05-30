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
    protected static ?string $navigationLabel = 'គ្រប់គ្រងសាខា';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('ព័ត៌មានសាខាហាង')->schema([
                    // 🛠️ សំខាន់បំផុត៖ ត្រូវមាន TextInput សម្រាប់ code និង name ដូចខាងក្រោមនេះ
                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('ឧទាហរណ៍៖ B001')
                        ->label('កូដសាខា'),

                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('ឧទាហរណ៍៖ សាខាភ្នំពេញ')
                        ->label('ឈ្មោះសាខា'),

                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->label('លេខទូរស័ព្ទ'),

                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->label('អ៊ីមែល'),

                    Forms\Components\Textarea::make('address')
                        ->columnSpanFull()
                        ->label('អាសយដ្ឋាន'),

                    Forms\Components\Toggle::make('status')
                        ->default(true)
                        ->label('ស្ថានភាពបើកដំណើរការ'),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->searchable()->label('កូដសាខា'),
                Tables\Columns\TextColumn::make('name')->searchable()->label('ឈ្មោះសាខា'),
                Tables\Columns\TextColumn::make('phone')->label('លេខទូរស័ព្ទ'),
                Tables\Columns\IconColumn::make('status')->boolean()->label('ដំណើរការ'),
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
            'index' => Pages\ListBranches::route('/'),
            'create' => Pages\CreateBranch::route('/create'),
            'edit' => Pages\EditBranch::route('/{record}/edit'),
        ];
    }
}
