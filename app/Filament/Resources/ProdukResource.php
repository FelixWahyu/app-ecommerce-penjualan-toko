<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Filament\Resources\ProdukResource\RelationManagers;
use App\Models\Produk;
use Faker\Core\Color;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Markdown;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $navigationLabel = 'Data Produk';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Produk Information')->schema([
                        TextInput::make('kode_produk')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('nama_produk')
                            ->required()
                            ->maxLength(255),
                        MarkdownEditor::make('deskripsi')
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('produks'),
                    ])->columns(2),

                    Section::make('Images')->schema([
                        FileUpload::make('image_produk')
                            ->directory('produks')
                            ->maxFiles(5)
                            ->reorderable(),
                    ])
                ])->columnSpan(2),

                Group::make()->schema([
                    Section::make('Harga')->schema([
                        TextInput::make('harga_beli')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('harga_jual')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                    ]),

                    Section::make('Associations')->schema([
                        Select::make('id_category')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('category', 'nama_category'),
                        Select::make('id_unit')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('unit', 'nama_unit'),
                        TextInput::make('stock')
                            ->required()
                            ->numeric(),
                        Toggle::make('is_active')
                            ->required()
                            ->default(true)
                    ])
                ])->columnSpan(1)
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_produk'),
                TextColumn::make('nama_produk')
                    ->searchable(),
                TextColumn::make('category.nama_category')
                    ->sortable(),
                TextColumn::make('harga_beli')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('harga_jual')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('stock')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->translatedFormat('d F Y H:i')),
                // TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'nama_category'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->color('info'),
                Tables\Actions\EditAction::make()
                    ->color('warning'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
