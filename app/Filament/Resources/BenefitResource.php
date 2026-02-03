<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BenefitResource\Pages;
use App\Models\Benefit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BenefitResource extends Resource
{
    protected static ?string $model = Benefit::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationLabel = 'Benefit';

    protected static ?string $modelLabel = 'Benefit';

    protected static ?string $pluralModelLabel = 'Benefit';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?int $navigationSort = 4;

    // Form for Create and Edit
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Field to input the name of the benefit
                Forms\Components\TextInput::make('name')
                    ->label('Benefit Name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    // Table to display Benefits data
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name') // Display the name of the benefit
                    ->label('Benefit Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                // Optionally, you can add filters here
            ])
            ->actions([
                // Action for editing the benefit
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Bulk delete action
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // You can define relations here, if any
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBenefits::route('/'),
            'create' => Pages\CreateBenefit::route('/create'),
            'edit' => Pages\EditBenefit::route('/{record}/edit'),
        ];
    }
}
