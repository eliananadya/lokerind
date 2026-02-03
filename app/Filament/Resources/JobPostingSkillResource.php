<?php

namespace App\Filament\Resources;

use App\Models\JobPostingSkills;
use App\Models\JobPostings;
use App\Models\Skills;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\JobPostingSkillResource\Pages;

class JobPostingSkillResource extends Resource
{
    protected static ?string $model = JobPostingSkills::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Keahlian yang Dibutuhkan';

    protected static ?string $modelLabel = 'Keahlian yang Dibutuhkan';

    protected static ?string $pluralModelLabel = 'Keahlian yang Dibutuhkan';

    protected static ?string $navigationGroup = 'Relasi & Detail';

    protected static ?int $navigationSort = 2;

    // Form for creating/editing a Job Posting Skill
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Select for Job Posting
                Forms\Components\Select::make('job_posting_id')
                    ->label('Job Posting')
                    ->relationship('jobPosting', 'title')  // Relationship with JobPostings
                    ->required(),

                // Select for Skill
                Forms\Components\Select::make('skills_id')
                    ->label('Skill')
                    ->relationship('skill', 'name')  // Relationship with Skills
                    ->required(),
            ]);
    }

    // Table to display Job Posting Skills
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jobPosting.title')
                    ->label('Job Posting')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('skill.name')
                    ->label('Skill')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                // Add filters if necessary
            ])
            ->actions([
                Tables\Actions\EditAction::make(),  // Edit action for job posting skills
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
            // Add relations here if necessary
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobPostingSkills::route('/'),
            'create' => Pages\CreateJobPostingSkill::route('/create'),
            'edit' => Pages\EditJobPostingSkill::route('/{record}/edit'),
        ];
    }
}
