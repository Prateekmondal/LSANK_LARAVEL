<?php

namespace App\Filament\Resources\ExplosiveUsedResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class JcrRelationManager extends RelationManager
{
    protected static string $relationship = 'jcr';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fieldName'),
                Tables\Columns\TextColumn::make('wellNo'),
                Tables\Columns\TextColumn::make('indentNo'),
            ])
            ->headerActions([
                Tables\Actions\AssociateAction::make()->preloadRecordSelect(),
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
