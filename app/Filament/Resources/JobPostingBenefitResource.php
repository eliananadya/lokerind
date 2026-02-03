<?php

namespace App\Filament\Resources;

use App\Models\JobPostingBenefit;
use App\Models\JobPostings;
use App\Models\Benefits;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\JobPostingBenefitResource\Pages;

class JobPostingBenefitResource extends Resource
{
    protected static ?string $model = JobPostingBenefit::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationLabel = 'Benefit Lowongan';

    protected static ?string $modelLabel = 'Benefit Lowongan';

    protected static ?string $pluralModelLabel = 'Benefit Lowongan';

    protected static ?string $navigationGroup = 'Relasi & Detail';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('job_posting_id')
                    ->label('Job Posting')
                    ->relationship('jobPosting', 'title')  // Relationship with JobPostings
                    ->required(),

                Forms\Components\Select::make('benefit_id')
                    ->label('Benefit')
                    ->relationship('benefit', 'name')  // Relationship with Benefits
                    ->required(),

                Forms\Components\TextInput::make('benefit_type')
                    ->label('Benefit Type')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('amount')
                    ->label('Amount')
                    ->type('number')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jobPosting.title')
                    ->label('Job Posting')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('benefit.name')
                    ->label('Benefit')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('benefit_type')
                    ->label('Benefit Type')
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->sortable(),
            ])
            ->filters([
                // Add any custom filters here if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),  // Edit action for job posting benefits
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
            // If you have any relations to show here, you can define them
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobPostingBenefits::route('/'),
            'create' => Pages\CreateJobPostingBenefit::route('/create'),
            'edit' => Pages\EditJobPostingBenefit::route('/{record}/edit'),
        ];
    }
}
