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
                Forms\Components\Section::make('ព័ត៌មានវិក្កយបត្រ')->schema([
                    Forms\Components\TextInput::make('invoice_no')
                        ->default('INV-' . strtoupper(uniqid()))
                        ->required()
                        ->readonly()
                        ->label('លេខវិក្កយបត្រ'),

                    Forms\Components\Select::make('order_id')
                        ->relationship('order', 'id')
                        ->required()
                        ->reactive()
                        // នៅពេលរើសលេខកុម្ម៉ង់ ឱ្យវាទៅទាញយកទឹកប្រាក់សរុបមកធ្វើជា Subtotal រួចគណនាពន្ធ និងតម្លៃចុងក្រោយ
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            $order = Order::find($state);
                            $subtotal = $order ? (float)$order->total_amount : 0;
                            $tax = $subtotal * 0.10; // ពន្ធ 10%

                            $set('subtotal', $subtotal);
                            $set('tax', $tax);
                            $set('grand_total', $subtotal + $tax);
                        })
                        ->label('លេខកុម្ម៉ង់ (Order ID)'),

                    Forms\Components\Select::make('cashier_id')
                        ->relationship('cashier', 'fullname')
                        ->required()
                        ->label('អ្នកគិតលុយ (Cashier)'),

                    Forms\Components\Select::make('payment_status')
                        ->options([
                            'Unpaid' => 'មិនទាន់បង់ប្រាក់',
                            'Paid' => 'បានបង់ប្រាក់រួចរាល់',
                        ])
                        ->default('Unpaid')
                        ->required()
                        ->label('ស្ថានភាពការទូទាត់'),
                ])->columns(2),

                Forms\Components\Section::make('តួលេខសរុបសាច់ប្រាក់')->schema([
                    Forms\Components\TextInput::make('subtotal')
                        ->numeric()
                        ->prefix('$')
                        ->disabled()
                        ->dehydrated()
                        ->label('សរុបរង'),

                    Forms\Components\TextInput::make('discount')
                        ->numeric()
                        ->default(0)
                        ->reactive()
                        ->prefix('$')
                        // នៅពេលបញ្ចូលចំនួនបញ្ចុះតម្លៃ ឱ្យវាគណនា Grand Total ឡើងវិញ
                        ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                            $subtotal = (float)$get('subtotal');
                            $tax = (float)$get('tax');
                            $discount = (float)$state;
                            $set('grand_total', ($subtotal + $tax) - $discount);
                        })
                        ->label('បញ្ចុះតម្លៃ (Discount)'),

                    Forms\Components\TextInput::make('tax')
                        ->numeric()
                        ->prefix('$')
                        ->disabled()
                        ->dehydrated()
                        ->label('ពន្ធដារ (VAT 10%)'),

                    Forms\Components\TextInput::make('grand_total')
                        ->numeric()
                        ->prefix('$')
                        ->disabled()
                        ->dehydrated()
                        ->label('ទឹកប្រាក់ត្រូវទូទាត់សរុប'),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_no')->searchable()->label('លេខវិក្កយបត្រ'),
                Tables\Columns\TextColumn::make('order.id')->label('លេខកុម្ម៉ង់'),
                Tables\Columns\TextColumn::make('cashier.fullname')->label('អ្នកគិតលុយ'),
                Tables\Columns\TextColumn::make('grand_total')->money('USD')->label('ទឹកប្រាក់សរុប'),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'danger' => 'Unpaid',
                        'success' => 'Paid',
                    ])
                    ->label('ស្ថានភាព'),
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
