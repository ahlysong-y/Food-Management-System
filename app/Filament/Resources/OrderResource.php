<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\MenuItem; // 🛠️ Added this model import to resolve undefined class errors
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
                Forms\Components\Section::make('General Information')->schema([
                    Forms\Components\Select::make('branch_id')
                        ->relationship('branch', 'name')
                        ->required()
                        ->label('Branch'),
                    Forms\Components\Select::make('table_id')
                        ->relationship('table', 'table_no')
                        ->label('Table Number'),
                    Forms\Components\DateTimePicker::make('order_date')
                        ->default(now())
                        ->required()
                        ->label('Order Date'),
                    Forms\Components\Select::make('status')
                        ->options([
                            'Pending' => 'Pending',
                            'Cooking' => 'Cooking',
                            'Served' => 'Served',
                            'Completed' => 'Completed',
                            'Cancelled' => 'Cancelled',
                        ])
                        ->default('Pending')
                        ->required()
                        ->label('Status'),
                ])->columns(2),

                Forms\Components\Section::make('Ordered Items List')->schema([
                    Forms\Components\Repeater::make('orderDetails')
                        ->relationship()
                        ->schema([
                            Forms\Components\Select::make('menu_item_id')
                                ->relationship('menuItem', 'name')
                                ->required()
                                ->reactive()
                                // When an item is selected, fetch its selling price to fill unit_price and calculate the subtotal immediately
                                ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                    $price = MenuItem::find($state)?->selling_price ?? 0;
                                    $qty = (int)($get('qty') ?? 1);
                                    $set('unit_price', $price);
                                    $set('subtotal', $price * $qty);
                                })
                                ->label('Menu Item'),

                            Forms\Components\TextInput::make('qty')
                                ->numeric()
                                ->default(1)
                                ->required()
                                ->reactive()
                                // When quantity changes, recalculate the item subtotal
                                ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                    $price = (float)($get('unit_price') ?? 0);
                                    $set('subtotal', $price * (int)$state);
                                })
                                ->label('Quantity'),

                            Forms\Components\TextInput::make('unit_price')
                                ->numeric()
                                ->prefix('$')
                                ->disabled()
                                ->dehydrated()
                                ->label('Unit Price'),

                            Forms\Components\TextInput::make('subtotal')
                                ->numeric()
                                ->prefix('$')
                                ->disabled()
                                ->dehydrated()
                                ->label('Subtotal'),
                        ])
                        ->columns(4)
                        ->createItemButtonLabel('Add Menu Item')
                        ->live() // Forces reactive updates to evaluate across the entire form state

                        // Function to calculate the final Total Amount across all repeater items combined
                        ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                            $repeaters = $get('orderDetails') ?? [];
                            $total = 0;
                            foreach ($repeaters as $repeater) {
                                $total += (float)($repeater['subtotal'] ?? 0);
                            }
                            $set('total_amount', $total);
                        }),

                    // Displays the final total amount to be paid
                    Forms\Components\TextInput::make('total_amount')
                        ->numeric()
                        ->prefix('$')
                        ->disabled()
                        ->dehydrated()
                        ->label('Total Payable Amount'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Order ID'),
                Tables\Columns\TextColumn::make('table.table_no')->label('Table Number'),
                Tables\Columns\TextColumn::make('order_date')->dateTime()->label('Order Date'),
                Tables\Columns\TextColumn::make('status')->label('Status'),
                Tables\Columns\TextColumn::make('total_amount')->money('USD')->label('Total Amount'),
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
