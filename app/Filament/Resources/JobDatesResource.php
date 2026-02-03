<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobDatesResource\Pages;
use App\Models\JobDates;
use App\Models\JobPostings;
use App\Models\Days;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;

class JobDatesResource extends Resource
{
    protected static ?string $model = JobDates::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Jadwal Kerja';

    protected static ?string $modelLabel = 'Jadwal Kerja';

    protected static ?string $pluralModelLabel = 'Jadwal Kerja';

    protected static ?string $navigationGroup = 'Relasi & Detail';

    protected static ?int $navigationSort = 4;

    // Define the form for creating/editing Job Dates
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->label('Date')
                    ->required(),  // Date when the job will be available

                Forms\Components\TimePicker::make('start_time')
                    ->label('Start Time')
                    ->required(),  // Start time for the job

                Forms\Components\TimePicker::make('end_time')
                    ->label('End Time')
                    ->required(),  // End time for the job

                Forms\Components\Select::make('job_posting_id')
                    ->label('Job Posting')
                    ->relationship('jobPosting', 'title')  // Relationship with JobPostings
                    ->required(),

                Forms\Components\Select::make('days_id')
                    ->label('Day')
                    ->relationship('days', 'name')  // Relationship with Days model
                    ->required(),  // Select day for the job date
            ]);
    }

    // Define the table for listing Job Dates
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jobPosting.title')
                    ->label('Job Posting')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('days.name')
                    ->label('Day')
                    ->sortable(),

                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_time')
                    ->label('Start Time')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_time')
                    ->label('End Time')
                    ->sortable(),
            ])
            ->filters([
                // Add custom filters if necessary
            ])
            ->actions([
                Tables\Actions\EditAction::make(),  // Edit action for job dates
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // Define any relations to be displayed (if necessary)
    public static function getRelations(): array
    {
        return [
            // Define relations here if required
        ];
    }

    // Define the pages for CRUD operations
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobDates::route('/'),
            'create' => Pages\CreateJobDates::route('/create'),
            'edit' => Pages\EditJobDates::route('/{record}/edit'),
        ];
    }
}
