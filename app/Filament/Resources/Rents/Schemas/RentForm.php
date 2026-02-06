<?php

namespace App\Filament\Resources\Rents\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('user-id')
                    ->required()
                    ->numeric(),
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                TextInput::make('kk')
                    ->required(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Select::make('status')
                    ->options(['cicilan' => 'Cicilan', 'cash' => 'Cash'])
                    ->required(),
                Select::make('confirmation')
                    ->options(['proses' => 'Proses', 'berhasil' => 'Berhasil'])
                    ->required(),
                Select::make('payment_status')
                    ->options(['proses' => 'Proses', 'lunas' => 'Lunas'])
                    ->required(),
            ]);
    }
}
