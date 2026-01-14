<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChecklistForwardResource\Pages;
use App\Models\ChecklistForward;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ChecklistForwardResource extends Resource
{
    protected static ?string $model = ChecklistForward::class;

    protected static ?string $navigationIcon = 'heroicon-o-share';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('explosive_checklist_id')
                    ->relationship('checklist', 'well_no')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('from_user_id')
                    ->relationship('fromUser', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('to_user_id')
                    ->relationship('toUser', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('forwarded_at'),
                TextInput::make('message'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('explosive_checklist_id'),
                TextColumn::make('fromUser.name')->label('From'),
                TextColumn::make('toUser.name')->label('To'),
                TextColumn::make('forwarded_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChecklistForwards::route('/'),
            'create' => Pages\CreateChecklistForward::route('/create'),
            'view' => Pages\ViewChecklistForward::route('/{record}'),
            'edit' => Pages\EditChecklistForward::route('/{record}/edit'),
        ];
    }
}
