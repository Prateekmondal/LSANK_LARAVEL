<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExternalSignatureResource\Pages;
use App\Models\ExternalSignature;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExternalSignatureResource extends Resource
{
    protected static ?string $model = ExternalSignature::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('explosive_checklist_id')
                    ->relationship('checklist', 'well_no')
                    ->searchable()
                    ->preload(),
                TextInput::make('name')->required(),
                TextInput::make('designation'),
                TextInput::make('cpf_no'),
                TextInput::make('email')->email(),
                DatePicker::make('signed_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name'),
                TextColumn::make('designation'),
                TextColumn::make('cpf_no'),
                TextColumn::make('email'),
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
            'index' => Pages\ListExternalSignatures::route('/'),
            'create' => Pages\CreateExternalSignature::route('/create'),
            'view' => Pages\ViewExternalSignature::route('/{record}'),
            'edit' => Pages\EditExternalSignature::route('/{record}/edit'),
        ];
    }
}
