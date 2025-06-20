<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Produk;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Number;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use Filament\Support\Enums\ActionSize;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Order Information')->schema([
                        Select::make('id_user')
                            ->required()
                            ->label('Pelanggan')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload(),
                        DateTimePicker::make('tanggal_pemesanan')
                            ->default(now())
                            ->timezone('Asia/Jakarta'),
                        Select::make('id_metode_pembayaran')
                            ->label('Metode Pembayaran')
                            ->required()
                            ->options([
                                'cash' => 'Cash',
                                'transfer' => 'Transfer Bank'
                            ]),
                        Select::make('status_pembayaran')
                            ->label('Status Pembayaran')
                            ->required()
                            ->options([
                                'pending' => 'Pending',
                                'sukses' => 'Sukses',
                                'gagal' => 'Gagal'
                            ])
                            ->default('pending'),
                        ToggleButtons::make('status')
                            ->options([
                                'baru' => 'Baru',
                                'proses' => 'Proses',
                                'dikirim' => 'Dikirim',
                                'sukses' => 'Sukses',
                                'canceled' => 'Canceled'
                            ])
                            ->default('baru')
                            ->required()
                            ->inline()
                            ->colors([
                                'baru' => 'primary',
                                'proses' => 'warning',
                                'dikirim' => 'info',
                                'sukses' => 'success',
                                'canceled' => 'danger'
                            ])
                            ->icons([
                                'baru' => 'heroicon-m-shopping-bag',
                                'proses' => 'heroicon-m-arrow-path',
                                'dikirim' => 'heroicon-m-truck',
                                'sukses' => 'heroicon-m-check-badge',
                                'canceled' => 'heroicon-m-x-circle'
                            ])
                            ->columnSpan(2),
                        Select::make('currency')
                            ->label('Mata Uang')
                            ->options([
                                'usd' => 'USD',
                                'idr' => 'IDR',
                                'eur' => 'EUR'
                            ])
                            ->default('idr')
                            ->required(),
                        Select::make('metode_pengiriman')
                            ->required()
                            ->options([
                                'gosend' => 'Gosend',
                                'cod' => 'Cash On Deliveri',
                                'ambil-ditoko' => 'Ambil ditoko'
                            ]),
                        Textarea::make('keterangan')
                            ->columnSpanFull()
                    ])->columns(2),

                    Section::make('Order Items')->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Placeholder::make('stok_warning')
                                    ->content(function (Get $get) {
                                        $produk = \App\Models\Produk::find($get('id_produk'));
                                        if (!$produk) return '';

                                        if ($produk->stock <= 0) {
                                            return 'Stok produk ini HABIS!';
                                        } elseif ($produk->stock <= 5) {
                                            return 'Stok hampir habis! Tersisa ' . $produk->stock;
                                        }

                                        return '';
                                    })
                                    ->columnSpanFull()
                                    ->reactive(),
                                Select::make('id_produk')
                                    ->relationship('produk', 'nama_produk')
                                    ->searchable()
                                    ->required()
                                    ->preload()
                                    ->distinct()
                                    ->reactive()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->afterStateUpdated(fn($state, Set $set) => $set('kode_produk', Produk::find($state)?->kode_produk ?? 'Tidak ada'))
                                    ->afterStateUpdated(fn($state, Set $set) => $set('jumlah_satuan', Produk::find($state)?->harga_jual ?? 0))
                                    ->afterStateUpdated(fn($state, Set $set) => $set('jumlah_total', Produk::find($state)?->harga_jual ?? 0))
                                    ->columnSpan(4),
                                TextInput::make('kode_produk')
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(2),
                                TextInput::make('quantity')
                                    ->required()
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('jumlah_total', $state * $get('jumlah_satuan')))
                                    ->rule(function (Get $get) {
                                        $produkId = $get('id');
                                        $produk = Produk::find($produkId);
                                        return $produk ? 'max:' . $produk->stock : null;
                                    })
                                    ->columnSpan(2),
                                TextInput::make('jumlah_satuan')
                                    ->required()
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(2),
                                TextInput::make('jumlah_total')
                                    ->numeric()
                                    ->required()
                                    ->dehydrated()
                                    ->columnSpan(2),
                            ])->columns(12),
                        Placeholder::make('grand_total_placeholder')
                            ->label('Grand Total')
                            ->content(function (Get $get, Set $set) {
                                $total = 0;
                                if (!$repeaters = $get('items')) {
                                    return $total;
                                }

                                foreach ($repeaters as $key => $repeater) {
                                    $total += $get("items.{$key}.jumlah_total");
                                }

                                $set('grand_total', $total);
                                return Number::currency($total, 'IDR');
                            }),
                        Hidden::make('grand_total')
                            ->default(0)
                    ])
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal_pemesanan')
                    ->sortable()
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->translatedFormat('d F Y')),
                TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('grand_total')
                    ->sortable()
                    ->numeric()
                    ->money('IDR'),
                TextColumn::make('id_metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status_pembayaran')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'sukses' => 'success',
                        'gagal' => 'danger'
                    }),
                TextColumn::make('metode_pengiriman')
                    ->sortable()
                    ->searchable(),
                SelectColumn::make('status')
                    ->options([
                        'baru' => 'Baru',
                        'proses' => 'Proses',
                        'dikirim' => 'Dikirim',
                        'sukses' => 'Sukses',
                        'canceled' => 'Canceled'
                    ])
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('info'),
                    Tables\Actions\EditAction::make()
                        ->color('warning'),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->label('More actions')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size(ActionSize::Small)
                    ->color('warning')
                    ->button(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
