<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use App\Models\Rent;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pemilik')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Produk')
                    ->searchable(),
                TextColumn::make('promo_price')
                    ->label('Harga Promo')
                    ->prefix('Rp ')
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->prefix('Rp ')
                    ->sortable(),
                TextColumn::make('unit')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'tahun' => 'success',
                        'bulan' => 'warning',
                        'hari' => 'pink',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'tersedia' => 'success',
                        'penuh' => 'danger',
                        'default' => 'info'
                    }),
                TextColumn::make('qty')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_verify')
                    ->boolean(),
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
                ViewAction::make()
                    ->icon(false)
                    ->label('Lihat'),
                EditAction::make()
                    ->icon(false)
                    ->label('Edit'),
                Action::make('sewa')
                    ->label('Sewa')
                    ->color('success')
                    ->modalHeading('Form Pengajuan Sewa')
                    ->modalAutofocus(false)
                    ->form([
                        Wizard::make([
                            Step::make('Data Sewa')
                                ->schema([
                                    TextInput::make('rent.code')
                                        ->label('Kode Sewa')
                                        ->default(fn() => 'RENT-' . Str::upper(Str::random(6)))
                                        ->disabled()
                                        ->dehydrated()
                                        ->required(),

                                    TextInput::make('user_name')
                                        ->label('Pelanggan')
                                        ->default(fn() => Auth::user()->name)
                                        ->disabled()
                                        ->dehydrated(false),

                                    Hidden::make('user_id')
                                        ->default(fn() => Auth::user()->id)
                                        ->dehydrated()
                                        ->required(),

                                    TextInput::make('product_name')
                                        ->label('produk')
                                        ->default(fn($record) => $record->name)
                                        ->disabled()
                                        ->dehydrated(false),

                                    Hidden::make('product_id')
                                        ->label('Produk')
                                        ->default(fn($record) => $record->id)
                                        ->disabled()
                                        ->dehydrated()
                                        ->required(),

                                    TextInput::make('rent.price')
                                        ->label('Harga')
                                        ->default(fn($record) => $record->promo_price ?? $record->price)
                                        ->numeric()
                                        ->prefix('Rp ')
                                        ->disabled()
                                        ->dehydrated()
                                        ->required(),

                                    DatePicker::make('start_date')
                                        ->label('Tanggal Mulai')
                                        ->live()
                                        ->afterStateUpdated(function ($record, $state, Set $set) {
                                            if (! $state) {
                                                return;
                                            }
                                            if (($record->unit === 'tahun')) {
                                                $endDate = Carbon::parse($state)->addDays(365);
                                            }
                                            if (($record->unit === 'bulan')) {
                                                $endDate = Carbon::parse($state)->addDays(30);
                                            }
                                            if (($record->unit === 'hari')) {
                                                $endDate = Carbon::parse($state)->addDay();
                                            }
                                            $set('end_date', $endDate);
                                        })
                                        ->minDate(today())
                                        ->required(),

                                    DatePicker::make('end_date')
                                        ->label('Tanggal Selesai')
                                        ->dehydrated()
                                        ->required(),

                                    FileUpload::make('kk')
                                        ->label('Nomor KK')
                                        ->directory('kk')
                                        ->required(),

                                    Select::make('rent.status')
                                        ->live()
                                        ->label('Metode Pembayaran')
                                        ->options([
                                            'cicilan' => 'Cicilan',
                                            'cash' => 'Cash',
                                        ])
                                        ->afterStateUpdated(function ($record, $state, Set $set) {
                                            $currPrice = $record->promo_price ?? $record->price;


                                            if ($state === 'cicilan') {
                                                return $set('payment.price', $currPrice * 0.5) && $set('payment.status', 'dp');
                                            }
                                            return $set('payment.price', $currPrice) && $set('payment.status', 'pelunasan');
                                        })
                                        // ->default('cash')
                                        ->required(),
                                    Section::make('Syarat dan Ketentuan')
                                        ->schema([
                                            ViewField::make('agreements')
                                                // ->content(function ($record) {
                                                //     if (! $record || $record->agreements->isEmpty()) {
                                                //         return 'Tidak ada perjanjian yang tersedia.';
                                                //     }

                                                //     $html = $record->agreements->map(function ($agreement, $index) {
                                                //         return '
                                                //             <div class="mb-4 p-4 border rounded-lg prose prose-ul:list-disc prose-ol:list-decimal">
                                                //                 <div class="font-bold text-base mb-3">
                                                //                     ' . ($index + 1) . '. ' . e($agreement->title) . '
                                                //                 </div>

                                                //                 <div class="prose max-w-none text-sm prose-ul:list-disc prose-ol:list-decimal">
                                                //                     ' . $agreement->desc . '
                                                //                 </div>
                                                //                 <hr />
                                                //                 <br />
                                                //             </div>
                                                //         ';
                                                //     })->implode('');

                                                //     return new HtmlString($html);
                                                // })
                                                ->view('filament.pages.agreement')
                                                ->columnSpanFull(),
                                            // Radio::make('isAgree')
                                            //     ->label(false)
                                            //     ->options([
                                            //         'true' => 'Saya setuju dengan syarat dan ketentuan yang berlaku terhadap pengajuan sewa ini.',
                                            //     ])->required(),
                                            Checkbox::make('setuju')
                                                ->accepted()

                                        ])->columnSpanFull()
                                ])
                                ->columns(2),

                            Step::make('Detail Sewa')
                                ->schema([
                                    Repeater::make('rentDetails')
                                        ->schema([
                                            TextInput::make('name')
                                                ->label('Nama Lengkap')
                                                ->required(),

                                            Select::make('identity')
                                                ->label('Jenis Identitas')
                                                ->options([
                                                    'ktp' => 'KTP',
                                                    'sim' => 'SIM',
                                                ])
                                                ->required(),

                                            FileUpload::make('identity_file')
                                                ->label('Lampiran Identitas')
                                                ->disk('public')
                                                ->directory('identity_files')
                                                ->required(),
                                        ])
                                        ->minItems(1)
                                        ->columns(3),
                                ]),

                            Step::make('Pembayaran Sewa')
                                ->schema([
                                    TextInput::make('payment.code')
                                        ->label('Kode Pembayaran')
                                        ->default(fn() => 'TRX-' . Str::upper(Str::random(6)))
                                        ->dehydrated()
                                        ->disabled()
                                        ->required(),
                                    TextInput::make('payment.price')
                                        ->label('Nominal')
                                        ->reactive()
                                        ->dehydrated()
                                        ->disabled(),
                                    TextInput::make('payment.status')
                                        ->label('Tujuan Pembayaran'),
                                    FileUpload::make('proof_payment')
                                        ->label('Bukti Pembayaran')
                                        ->disk('public')
                                        ->directory('proof_payment')
                                        ->columnSpanFull()
                                        ->required(),
                                ])
                        ]),
                    ])
                    ->action(function (array $data, Product $product) {
                        DB::transaction(function () use ($data, $product) {
                            $product->refresh()->lockForUpdate();

                            if ($product->status !== 'tersedia' || $product->qty < 1) {
                                throw new \Exception('Produk sudah tidak tersedia.');
                            }
                            $rent = Rent::create([
                                'code' => $data['rent']['code'],
                                'user_id' => $data['user_id'],
                                'product_id' => $data['product_id'],
                                'kk' => $data['kk'],
                                'start_date' => $data['start_date'],
                                'end_date' => $data['end_date'],
                                'price' => $data['rent']['price'],
                                'status' => $data['rent']['status'],
                                'confirmation' => 'proses',
                                'payment_status' => 'proses',
                            ]);

                            $rent->rentDetails()->createMany($data['rentDetails']);
                            $rent->rentPayments()->create([
                                'code' => $data['payment']['code'],
                                'price' => $data['payment']['price'],
                                'proof_payment' => $data['proof_payment'],
                                'status' => $data['payment']['status'],
                                'confirmation' => 'proses'

                            ]);
                            $newQty = $product->qty - 1;
                            $product->update([
                                'qty' => $newQty,
                                'status' => $product->qty === 0 ? 'penuh' : 'tersedia',
                            ]);
                        });
                    })
                    ->successNotificationTitle('Data sewa berhasil dibuat. Silakan menunggu konfirmasi dari pemilik.')
                    ->failureNotificationTitle('Maaf, Data pengajuan sewa Anda gagal'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
