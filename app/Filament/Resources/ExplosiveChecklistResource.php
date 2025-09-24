<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExplosiveChecklistResource\Pages;
use App\Filament\Resources\ExplosiveChecklistResource\RelationManagers;
use App\Models\ExplosiveChecklist;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExplosiveChecklistResource extends Resource
{
    protected static ?string $model = ExplosiveChecklist::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('jcr_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('type')
                    ->required(),
                Forms\Components\TextInput::make('well_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('logging_unit_no')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('job_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('checklist_data')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('creator_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sign_status')
                    ->required(),
                Forms\Components\TextInput::make('external_sign_status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jcr_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('well_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('logging_unit_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('job_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('creator_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sign_status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('external_sign_status'),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExplosiveChecklists::route('/'),
            'create' => Pages\CreateExplosiveChecklist::route('/create'),
            'view' => Pages\ViewExplosiveChecklist::route('/{record}'),
            'edit' => Pages\EditExplosiveChecklist::route('/{record}/edit'),
        ];
    }
}
