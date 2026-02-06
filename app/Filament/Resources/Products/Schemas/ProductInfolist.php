<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                RepeatableEntry::make('productImages')
                    ->schema([
                        ImageEntry::make('image')
                            ->disk('public')
                            ->label(false)
                            ->columnSpanFull()
                    ])->columnSpanFull()->label('Gambar Produk'),
                TextEntry::make('user.name')
                    ->label('Pemilik')
                    ->numeric(),
                TextEntry::make('name')->label('Produk'),
                TextEntry::make('address')->label('Alamat'),
                TextEntry::make('desc')
                    ->html()
                    ->label('Deskripsi'),
                TextEntry::make('promo_price')
                    ->label('Harga Promo')
                    ->prefix('Rp ')
                    ->placeholder('-'),
                TextEntry::make('price')
                    ->label('Harga')
                    ->prefix('Rp '),
                TextEntry::make('unit')
                    ->label('Periode')
                    ->badge(),
                TextEntry::make('admin_fee')
                    ->label('Biaya Admin')
                    ->numeric()
                    ->suffix('%'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('qty')
                    ->numeric(),
                IconEntry::make('is_verify')
                    ->boolean(),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
