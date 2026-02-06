<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([
                        Select::make('seller_id')
                            ->options(fn() => [Auth::id() => Auth::user()->name])
                            ->default(fn() => Auth::id())
                            ->required(),
                        TextInput::make('name')
                            ->required(),
                        Toggle::make('is_verify')
                            ->required(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                TextInput::make('address')
                    ->columnSpanFull()
                    ->required(),
                RichEditor::make('desc')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('promo_price')
                    ->numeric()
                    ->default(null)
                    ->prefix('$'),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Select::make('unit')
                    ->options(['hari' => 'Hari', 'bulan' => 'Bulan', 'tahun' => 'Tahun'])
                    ->required(),
                TextInput::make('admin_fee')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(['penuh' => 'Penuh', 'tersedia' => 'Tersedia'])
                    ->required(),
                TextInput::make('qty')
                    ->required()
                    ->numeric()
                    ->default(1),
                Repeater::make('productImages')
                    ->relationship('productImages')
                    ->schema([
                        FileUpload::make('image')
                            ->disk('public')
                            ->directory('product_images')
                            ->image()
                            ->columnSpanFull(),
                    ])
                    ->defaultItems(1)
                    ->columnSpanFull()
            ]);
    }
}
