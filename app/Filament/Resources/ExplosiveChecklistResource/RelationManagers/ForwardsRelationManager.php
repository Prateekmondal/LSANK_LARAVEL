<?php

namespace App\Filament\Resources\ExplosiveChecklistResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ForwardsRelationManager extends RelationManager
{
    protected static string $relationship = 'forwards';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('from_user_id')
                    ->relationship('fromUser', 'name')
                    ->required(),
                Forms\Components\Select::make('to_user_id')
                    ->relationship('toUser', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('forwarded_at'),
                Forms\Components\Textarea::make('message')->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fromUser.name')->label('From'),
                Tables\Columns\TextColumn::make('toUser.name')->label('To'),
                Tables\Columns\TextColumn::make('forwarded_at')->dateTime(),
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
