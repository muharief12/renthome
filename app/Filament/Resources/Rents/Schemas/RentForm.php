<?php

namespace App\Filament\Resources\Rents\Schemas;

use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

class RentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Data Sewa')
                        ->schema([
                            TextInput::make('code')
                                ->required(),
                            TextInput::make('user-id')
                                ->required()
                                ->numeric(),
                            Select::make('product_id')
                                ->relationship('product', 'name')
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn($state, Set $set) => $set('price', Product::find($state)->price)),
                            TextInput::make('kk')
                                ->required(),
                            DatePicker::make('start_date')
                                ->required(),
                            DatePicker::make('end_date')
                                ->required(),
                            TextInput::make('price')
                                ->required()
                                ->numeric()
                                ->prefix('Rp '),
                            Select::make('status')
                                ->options(['cicilan' => 'Cicilan', 'cash' => 'Cash'])
                                ->required(),
                            Select::make('confirmation')
                                ->options(['proses' => 'Proses', 'berhasil' => 'Berhasil'])
                                ->required(),
                            Select::make('payment_status')
                                ->options(['proses' => 'Proses', 'lunas' => 'Lunas'])
                                ->required(),
                        ]),
                    Step::make('Detail Sewa')
                        ->schema([
                            Repeater::make('rentDetails')
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Nama Lengkap')
                                        ->required(),
                                    Select::make('identity')
                                        ->options([
                                            'KTP' => 'ktp',
                                            'SIM' => 'sim'
                                        ])
                                        ->required(),
                                    FileUpload::make('attachment')
                                        ->label('Lampiran KTP/SIM')
                                        ->disk('public')
                                        ->directory('identity')
                                        ->required()
                                ])
                        ])

                ])
                    ->columnSpanFull()
                    ->columns(2)
            ]);
    }
}
