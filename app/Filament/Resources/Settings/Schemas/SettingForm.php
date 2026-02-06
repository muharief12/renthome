<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('description')
                            ->default(null),
                        TextInput::make('fee')
                            ->required()
                            ->numeric(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                FileUpload::make('photo')
                    ->disk('public')
                    ->directory('logo')
                    ->default(null)
                    ->columnSpanFull(),
                RichEditor::make('term_condition')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
