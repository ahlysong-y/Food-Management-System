<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\MenuItem; // 🛠️ បានបន្ថែមការនាំចូល Model នេះដើម្បីដោះស្រាយ Error
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Orders';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('ព័ត៌មានទូទៅ')->schema([
                    Forms\Components\Select::make('branch_id')
                        ->relationship('branch', 'name')
                        ->required()
                        ->label('សាខា'),
                    Forms\Components\Select::make('table_id')
                        ->relationship('table', 'table_no')
                        ->label('លេខតុ'),
                    Forms\Components\DateTimePicker::make('order_date')
                        ->default(now())
                        ->required()
                        ->label('កាលបរិច្ឆេទ'),
                    Forms\Components\Select::make('status')
                        ->options([
                            'Pending' => 'រង់ចាំ (Pending)',
                            'Cooking' => 'កំពុងធ្វើ (Cooking)',
                            'Served' => 'បានជូនភ្ញៀវ (Served)',
                            'Completed' => 'រួចរាល់ (Completed)',
                            'Cancelled' => 'បោះបង់ (Cancelled)',
                        ])
                        ->default('Pending')
                        ->required()
                        ->label('ស្ថានភាព'),
                ])->columns(2),

                Forms\Components\Section::make('បញ្ជីមុខម្ហូបដែលកុម្ម៉ង់')->schema([
                    Forms\Components\Repeater::make('orderDetails')
                        ->relationship()
                        ->schema([
                            Forms\Components\Select::make('menu_item_id')
                                ->relationship('menuItem', 'name')
                                ->required()
                                ->reactive()
                                // នៅពេលរើសមុខម្ហូប ឱ្យវាទៅទាញតម្លៃលក់មកបំពេញក្នុង unit_price និងគណនា subtotal ភ្លាមៗ
                                ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                    $price = MenuItem::find($state)?->selling_price ?? 0;
                                    $qty = (int)($get('qty') ?? 1);
                                    $set('unit_price', $price);
                                    $set('subtotal', $price * $qty);
                                })
                                ->label('មុខម្ហូប'),

                            Forms\Components\TextInput::make('qty')
                                ->numeric()
                                ->default(1)
                                ->required()
                                ->reactive()
                                // នៅពេលប្តូរចំនួនចាន ឱ្យវាគណនា subtotal ឡើងវិញ
                                ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                    $price = (float)($get('unit_price') ?? 0);
                                    $set('subtotal', $price * (int)$state);
                                })
                                ->label('ចំនួន'),

                            Forms\Components\TextInput::make('unit_price')
                                ->numeric()
                                ->prefix('$')
                                ->disabled()
                                ->dehydrated()
                                ->label('តម្លៃរាយ'),

                            Forms\Components\TextInput::make('subtotal')
                                ->numeric()
                                ->prefix('$')
                                ->disabled()
                                ->dehydrated()
                                ->label('សរុបរង'),
                        ])
                        ->columns(4)
                        ->createItemButtonLabel('កុម្ម៉ង់មុខម្ហូបថែម')
                        ->live() // ធ្វើឱ្យការប្រែប្រួលរត់ទៅក្រឡេកមើលទំព័រទាំងមូល

                        // មុខងារគណនាទឹកប្រាក់សរុប (Total Amount) នៃគ្រប់មុខម្ហូបបញ្ចូលគ្នា
                        ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                            $repeaters = $get('orderDetails') ?? [];
                            $total = 0;
                            foreach ($repeaters as $repeater) {
                                $total += (float)($repeater['subtotal'] ?? 0);
                            }
                            $set('total_amount', $total);
                        }),

                    // បង្ហាញតម្លៃសរុបចុងក្រោយគេបង្អស់
                    Forms\Components\TextInput::make('total_amount')
                        ->numeric()
                        ->prefix('$')
                        ->disabled()
                        ->dehydrated()
                        ->label('ទឹកប្រាក់សរុបដែលត្រូវបង់'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('លេខកុម្ម៉ង់'),
                Tables\Columns\TextColumn::make('table.table_no')->label('លេខតុ'),
                Tables\Columns\TextColumn::make('order_date')->dateTime()->label('កាលបរិច្ឆេទ'),
                Tables\Columns\TextColumn::make('status')->label('ស្ថានភាព'),
                Tables\Columns\TextColumn::make('total_amount')->money('USD')->label('ទឹកប្រាក់សរុប'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
