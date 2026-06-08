<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExplosiveUsedResource\Pages;
use App\Models\explosiveUsed;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExplosiveUsedResource extends Resource
{
    protected static ?string $model = explosiveUsed::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('jcr_id')
                    ->relationship('jcr', 'wellNo')
                    ->searchable()
                    ->preload(),
                TextInput::make('explosive')->required(),
                TextInput::make('issued')->numeric(),
                TextInput::make('used')->numeric(),
                TextInput::make('returned')->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('explosive')->sortable(),
                TextColumn::make('issued'),
                TextColumn::make('used'),
                TextColumn::make('returned'),
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
            'index' => Pages\ListExplosiveUseds::route('/'),
            'create' => Pages\CreateExplosiveUsed::route('/create'),
            'view' => Pages\ViewExplosiveUsed::route('/{record}'),
            'edit' => Pages\EditExplosiveUsed::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\ExplosiveUsedResource\RelationManagers\JcrRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('jcr');
    }
}
