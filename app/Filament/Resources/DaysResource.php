<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DaysResource\Pages;
use App\Models\Days;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DaysResource extends Resource
{
    protected static ?string $model = Days::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Hari';

    protected static ?string $modelLabel = 'Hari';

    protected static ?string $pluralModelLabel = 'Hari';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?int $navigationSort = 6;

    // Menyusun form untuk create/edit
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Field untuk input nama hari
                Forms\Components\TextInput::make('name')
                    ->label('Day Name')
                    ->required()
                    ->maxLength(255),  // Membatasi panjang nama
            ]);
    }

    // Menyusun tabel untuk list data
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tampilkan nama hari
                Tables\Columns\TextColumn::make('name')
                    ->label('Day Name')
                    ->searchable()  // Memungkinkan pencarian
                    ->sortable(),   // Memungkinkan penyortiran
            ])
            ->filters([
                // Filter opsional bisa ditambahkan di sini
            ])
            ->actions([
                // Tombol aksi untuk mengedit hari
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Aksi untuk menghapus data secara massal
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Jika ada relasi lain, Anda bisa mendefinisikannya di sini
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDays::route('/'),
            'create' => Pages\CreateDays::route('/create'),
            'edit' => Pages\EditDays::route('/{record}/edit'),
        ];
    }
}
