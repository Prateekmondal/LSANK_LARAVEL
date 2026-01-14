<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChecklistSignatureResource\Pages;
use App\Models\ChecklistSignature;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ChecklistSignatureResource extends Resource
{
    protected static ?string $model = ChecklistSignature::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('explosive_checklist_id')
                    ->relationship('checklist', 'well_no')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('signature_type'),
                DatePicker::make('signed_at'),
                TextInput::make('comments'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('user.name')->label('User'),
                TextColumn::make('signature_type'),
                TextColumn::make('signed_at')->dateTime(),
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
            'index' => Pages\ListChecklistSignatures::route('/'),
            'create' => Pages\CreateChecklistSignature::route('/create'),
            'view' => Pages\ViewChecklistSignature::route('/{record}'),
            'edit' => Pages\EditChecklistSignature::route('/{record}/edit'),
        ];
    }
}
