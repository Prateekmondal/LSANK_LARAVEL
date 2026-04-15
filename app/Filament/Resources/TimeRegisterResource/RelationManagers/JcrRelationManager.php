<?php

namespace App\Filament\Resources\TimeRegisterResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class JcrRelationManager extends RelationManager
{
    protected static string $relationship = 'jcrs';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('fieldName')->required(),
                Forms\Components\TextInput::make('wellNo')->required(),
                Forms\Components\TextInput::make('indentNo'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fieldName'),
                Tables\Columns\TextColumn::make('wellNo'),
                Tables\Columns\TextColumn::make('indentNo'),
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
