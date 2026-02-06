<?php

namespace App\Filament\Resources\Rents\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code'),
                TextEntry::make('user-id')
                    ->numeric(),
                TextEntry::make('product.name')
                    ->label('Product'),
                TextEntry::make('kk'),
                TextEntry::make('start_date')
                    ->date(),
                TextEntry::make('end_date')
                    ->date(),
                TextEntry::make('price')
                    ->money(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('confirmation')
                    ->badge(),
                TextEntry::make('payment_status')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
