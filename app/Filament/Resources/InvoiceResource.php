<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Invoices';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Invoice Information')->schema([
                    Forms\Components\TextInput::make('invoice_no')
                        ->default('INV-' . strtoupper(uniqid()))
                        ->required()
                        ->readonly()
                        ->label('Invoice Number'),

                    Forms\Components\Select::make('order_id')
                        ->relationship('order', 'id')
                        ->required()
                        ->reactive()
                        // When selecting an order, automatically fetch its total amount as the subtotal, then calculate tax and grand total.
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            $order = Order::find($state);
                            $subtotal = $order ? (float)$order->total_amount : 0;
                            $tax = $subtotal * 0.10; // 10% Tax

                            $set('subtotal', $subtotal);
                            $set('tax', $tax);
                            $set('grand_total', $subtotal + $tax);
                        })
                        ->label('Order ID'),

                    Forms\Components\Select::make('cashier_id')
                        ->relationship('cashier', 'fullname')
                        ->required()
                        ->label('Cashier'),

                    Forms\Components\Select::make('payment_status')
                        ->options([
                            'Unpaid' => 'Unpaid',
                            'Paid' => 'Paid',
                        ])
                        ->default('Unpaid')
                        ->required()
                        ->label('Payment Status'),
                ])->columns(2),

                Forms\Components\Section::make('Financial Totals')->schema([
                    Forms\Components\TextInput::make('subtotal')
                        ->numeric()
                        ->prefix('$')
                        ->disabled()
                        ->dehydrated()
                        ->label('Subtotal'),

                    Forms\Components\TextInput::make('discount')
                        ->numeric()
                        ->default(0)
                        ->reactive()
                        ->prefix('$')
                        // When entering a discount value, recalculate the Grand Total accordingly.
                        ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                            $subtotal = (float)$get('subtotal');
                            $tax = (float)$get('tax');
                            $discount = (float)$state;
                            $set('grand_total', ($subtotal + $tax) - $discount);
                        })
                        ->label('Discount'),

                    Forms\Components\TextInput::make('tax')
                        ->numeric()
                        ->prefix('$')
                        ->disabled()
                        ->dehydrated()
                        ->label('Tax (VAT 10%)'),

                    Forms\Components\TextInput::make('grand_total')
                        ->numeric()
                        ->prefix('$')
                        ->disabled()
                        ->dehydrated()
                        ->label('Grand Total'),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_no')->searchable()->label('Invoice Number'),
                Tables\Columns\TextColumn::make('order.id')->label('Order ID'),
                Tables\Columns\TextColumn::make('cashier.fullname')->label('Cashier'),
                Tables\Columns\TextColumn::make('grand_total')->money('USD')->label('Grand Total'),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'danger' => 'Unpaid',
                        'success' => 'Paid',
                    ])
                    ->label('Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
