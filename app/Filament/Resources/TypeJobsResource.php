<?php

namespace App\Filament\Resources;

use App\Models\TypeJobs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\TypeJobsResource\Pages;

class TypeJobsResource extends Resource
{
    protected static ?string $model = TypeJobs::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Tipe Pekerjaan';

    protected static ?string $modelLabel = 'Tipe Pekerjaan';

    protected static ?string $pluralModelLabel = 'Tipe Pekerjaan';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Input untuk kolom 'name'
                Forms\Components\TextInput::make('name')
                    ->label('Job Type Name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tampilkan kolom 'name'
                Tables\Columns\TextColumn::make('name')
                    ->label('Job Type Name')
                    ->searchable() // Membolehkan pencarian berdasarkan nama
                    ->sortable(),  // Membolehkan penyortiran berdasarkan nama

                // Kolom yang menghitung jumlah Job Postings terkait (Opsional)
                Tables\Columns\TextColumn::make('prefferedTypeJobs_count')
                    ->label('Preferred Type Jobs Count')
                    ->counts('prefferedTypeJobs'), // Menampilkan jumlah Job Postings terkait
            ])
            ->filters([
                // Filter dapat ditambahkan jika perlu
            ])
            ->actions([
                // Tombol untuk mengedit data Job Type
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Aksi untuk menghapus banyak data sekaligus
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Anda bisa menambahkan relasi lain di sini jika diperlukan
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTypeJobs::route('/'),
            'create' => Pages\CreateTypeJobs::route('/create'),
            'edit' => Pages\EditTypeJobs::route('/{record}/edit'),
        ];
    }
}
