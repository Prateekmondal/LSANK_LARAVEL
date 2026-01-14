<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimeRegisterResource\Pages;
use App\Models\TimeRegister;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TimeRegisterResource extends Resource
{
    protected static ?string $model = TimeRegister::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('logging_unit_no')->required(),
                TextInput::make('indent_no'),
                TextInput::make('well_no')->required(),
                TextInput::make('rig_no'),
                DatePicker::make('well_indented_date'),
                TextInput::make('well_indented_time'),
                TextInput::make('job_carried_out')->columnSpan('full'),
                TextInput::make('rig_representative_email')->email(),
                TextInput::make('status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('logging_unit_no')->sortable()->searchable(),
                TextColumn::make('well_no')->sortable()->searchable(),
                TextColumn::make('rig_no')->sortable(),
                TextColumn::make('status')->sortable(),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['loggingChief', 'creator', 'jcr']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimeRegisters::route('/'),
            'create' => Pages\CreateTimeRegister::route('/create'),
            'view' => Pages\ViewTimeRegister::route('/{record}'),
            'edit' => Pages\EditTimeRegister::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\TimeRegisterResource\RelationManagers\JcrRelationManager::class,
        ];
    }
}
