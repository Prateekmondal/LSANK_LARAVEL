<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoggingUnitResource\Pages;
use App\Filament\Resources\LoggingUnitResource\RelationManagers;
use App\Models\LoggingUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoggingUnitResource extends Resource
{
    protected static ?string $model = LoggingUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('loggingUnit')
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('loggingUnit')
                    ->sortable(),
                Tables\Columns\TextColumn::make('logTypes.logType')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // ...
                
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\LogTypeRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoggingUnits::route('/'),
            'create' => Pages\CreateLoggingUnit::route('/create'),
            'view' => Pages\ViewLoggingUnit::route('/{record}'),
            'edit' => Pages\EditLoggingUnit::route('/{record}/edit'),
        ];
    }
}
