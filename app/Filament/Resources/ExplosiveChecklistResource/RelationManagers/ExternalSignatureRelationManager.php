<?php

namespace App\Filament\Resources\ExplosiveChecklistResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ExternalSignatureRelationManager extends RelationManager
{
    protected static string $relationship = 'externalSignature';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('designation'),
                Forms\Components\TextInput::make('cpf_no'),
                Forms\Components\TextInput::make('email')->email(),
                Forms\Components\DatePicker::make('signed_at'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('designation'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('signed_at')->dateTime(),
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
