<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoggingUnitTypeResource\Pages;
use App\Models\loggingUnitType;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LoggingUnitTypeResource extends Resource
{
    protected static ?string $model = loggingUnitType::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('loggingUnit_id')
                    ->relationship('loggingUnit', 'loggingUnit')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('logType_id')
                    ->relationship('logType', 'logType')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('loggingUnit_id'),
                TextColumn::make('logType_id'),
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
            'index' => Pages\ListLoggingUnitTypes::route('/'),
            'create' => Pages\CreateLoggingUnitType::route('/create'),
            'view' => Pages\ViewLoggingUnitType::route('/{record}'),
            'edit' => Pages\EditLoggingUnitType::route('/{record}/edit'),
        ];
    }
}
