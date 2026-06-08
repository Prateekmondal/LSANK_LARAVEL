<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogTypeResource\Pages;
use App\Filament\Resources\LogTypeResource\RelationManagers;
use App\Models\loggingUnit;
use App\Models\LogType;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BackedEnum;

class LogTypeResource extends Resource
{
    protected static ?string $model = LogType::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('logType')
                    ->required()
                    ->options([
                        'OHL'=>'OHL',
                        'CH'=>'CH',
                        'PL'=>'PL',
                        ]),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('logType')
                    ->sortable(),
                Tables\Columns\TextColumn::make('loggingUnits.loggingUnit')
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
            RelationManagers\LoggingUnitRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLogTypes::route('/'),
            'create' => Pages\CreateLogType::route('/create'),
            'view' => Pages\ViewLogType::route('/{record}'),
            'edit' => Pages\EditLogType::route('/{record}/edit'),
        ];
    }
}


