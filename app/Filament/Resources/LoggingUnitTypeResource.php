<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoggingUnitTypeResource\Pages;
use App\Filament\Resources\LoggingUnitTypeResource\RelationManagers;
use App\Models\LoggingUnitType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoggingUnitTypeResource extends Resource
{
    protected static ?string $model = LoggingUnitType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('loggingUnit'),
                Tables\Columns\TextColumn::make('logType'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoggingUnitTypes::route('/'),
            'create' => Pages\CreateLoggingUnitType::route('/create'),
            'edit' => Pages\EditLoggingUnitType::route('/{record}/edit'),
        ];
    }
}
