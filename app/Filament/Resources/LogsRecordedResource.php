<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogsRecordedResource\Pages;
use App\Models\logsRecorded;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LogsRecordedResource extends Resource
{
    protected static ?string $model = logsRecorded::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('jcr_id')
                    ->relationship('jcr', 'wellNo')
                    ->searchable()
                    ->preload(),
                TextInput::make('runNo')->numeric(),
                TextInput::make('logRecorded'),
                TextInput::make('bottomDepth')->numeric(),
                TextInput::make('topDepth')->numeric(),
                TextInput::make('toolNo'),
                TextInput::make('logQuality'),
                TextInput::make('bottomShotDepth')->numeric(),
                TextInput::make('topShotDepth')->numeric(),
                TextInput::make('charge'),
                TextInput::make('chargeNo'),
                TextInput::make('primaChord'),
                TextInput::make('primaChordQty')->numeric(),
                TextInput::make('fuse'),
                TextInput::make('fuseNo'),
                TextInput::make('fMf')->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('runNo'),
                TextColumn::make('logRecorded')->limit(50),
                TextColumn::make('bottomDepth'),
                TextColumn::make('topDepth'),
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
            'index' => Pages\ListLogsRecordeds::route('/'),
            'create' => Pages\CreateLogsRecorded::route('/create'),
            'view' => Pages\ViewLogsRecorded::route('/{record}'),
            'edit' => Pages\EditLogsRecorded::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\LogsRecordedResource\RelationManagers\JcrRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('jcr');
    }
}
