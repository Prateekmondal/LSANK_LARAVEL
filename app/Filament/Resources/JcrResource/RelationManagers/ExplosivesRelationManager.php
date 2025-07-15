<?php

namespace App\Filament\Resources\JcrResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExplosivesRelationManager extends RelationManager
{
    protected static string $relationship = 'explosives';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('explosive')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('issued')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('used')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('returned')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('explosive')
            ->columns([
                Tables\Columns\TextColumn::make('explosive'),
                Tables\Columns\TextColumn::make('issued'),
                Tables\Columns\TextColumn::make('used'),
                Tables\Columns\TextColumn::make('returned'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
