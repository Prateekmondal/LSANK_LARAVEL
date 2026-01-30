<?php

namespace App\Filament\Resources\JcrResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TimeRegisterRelationManager extends RelationManager
{
    protected static string $relationship = 'timeRegister';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('logging_unit_no')->required(),
                Forms\Components\TextInput::make('logging_unit_no')->required(),
                Forms\Components\TextInput::make('well_no')->required(),
                Forms\Components\TextInput::make('rig_no')->required(),
                Forms\Components\TextInput::make('well_indented_date')->required(),
                Forms\Components\TextInput::make('well_indented_time')->required(),
                Forms\Components\TextInput::make('well_taken_up_date')->required(),
                Forms\Components\TextInput::make('well_taken_up_time')->required(),
                Forms\Components\TextInput::make('well_handed_over_date')->required(),
                Forms\Components\TextInput::make('well_handed_over_time')->required(),
                Forms\Components\TextInput::make('job_carried_out')->required(),
                Forms\Components\TextInput::make('observations_by_logging_chief')->required(),
                Forms\Components\TextInput::make('rig_representative_observations')->required(),
                Forms\Components\Toggle::make('is_final_submitted'),
                Forms\Components\TextInput::make('rig_representative_email')->email(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('logging_unit_no')
            ->columns([
                Tables\Columns\TextColumn::make('logging_unit_no'),
                Tables\Columns\TextColumn::make('well_no'),
                Tables\Columns\TextColumn::make('rig_no'),
                Tables\Columns\IconColumn::make('is_final_submitted')->boolean(),
                Tables\Columns\TextColumn::make('rig_representative_email'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()->preloadRecordSelect(),
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
