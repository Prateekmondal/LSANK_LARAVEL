<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TimeRegistersRelationManager extends RelationManager
{
    protected static string $relationship = 'timeRegisters';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('logging_unit_no')->required(),
                Forms\Components\TextInput::make('well_no')->required(),
                Forms\Components\TextInput::make('rig_no'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('logging_unit_no'),
                Tables\Columns\TextColumn::make('well_no'),
                Tables\Columns\TextColumn::make('rig_no'),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
