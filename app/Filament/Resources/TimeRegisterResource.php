<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimeRegisterResource\Pages;
use App\Models\TimeRegister;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
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
                DatePicker::make('well_taken_up_date'),
                TextInput::make('well_taken_up_time'),
                DatePicker::make('well_handed_over_date'),
                TextInput::make('well_handed_over_time'),

                Textarea::make('job_carried_out')->columnSpan('full'),
                Textarea::make('observations_by_logging_chief')->columnSpan('full'),

                Select::make('logging_chief_id')
                    ->relationship('loggingChief', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('logging_chief_name'),
                TextInput::make('logging_chief_designation'),
                TextInput::make('logging_chief_signature'),
                DateTimePicker::make('logging_chief_signed_at'),

                TextInput::make('rig_representative_email')->email(),
                Textarea::make('rig_representative_observations'),
                TextInput::make('rig_representative_signature'),
                TextInput::make('rig_representative_name'),
                TextInput::make('rig_representative_designation'),
                DateTimePicker::make('rig_representative_signed_at'),

                TextInput::make('status'),
                TextInput::make('signature_token')->disabled(),
                Toggle::make('is_final_submitted'),
                DateTimePicker::make('final_submitted_at'),

                Select::make('created_by')
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload(),
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
