<?php

namespace App\Filament\Resources\Rents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Nama Penyewa')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->searchable(),
                ImageColumn::make('kk')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->label('Mulai Sewa')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Akhir Sewa')
                    ->date()
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.'))
                    ->prefix('Rp ')
                    ->sortable(),
                TextColumn::make('Total Pembayaran')
                    ->state(fn($record) => number_format($record->rentPayments->sum('price'), 0, ',', '.'))
                    ->prefix('Rp ')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('confirmation')
                    ->badge(),
                TextColumn::make('payment_status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
