<?php

namespace App\Filament\Resources\JcrResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LogsRelationManager extends RelationManager
{
    protected static string $relationship = 'logs';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('runNo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('logRecorded')
                    ->required()
                    ->options([
                        'Perforation(CCL)' => 'Perforation(CCL)',
                        'TTP(CCL)' => 'TTP(CCL)',
                        'Bridge Plug(CCL)' => 'Bridge Plug(CCL)',
                        'Junk Basket(CCL)' => 'Junk Basket(CCL)',
                        'GR-CCL' => 'GR-CCL',
                        'GR-TCL' => 'GR-TCL',
                        'SBT-GR-CCL' => 'SBT-GR-CCL',
                        'RBT-GR-CCL' => 'RBT-GR-CCL',
                        'Production Log' => 'Production Log',
                        'HDIL-ORIT-SP-GR' => 'HDIL-ORIT-SP-GR',
                        'ZDEN-CN-GR' => 'ZDEN-CN-GR',
                        'STAR-ORIT-GR' => 'STAR-ORIT-GR',
                        'XMAC-ORIT-GR' => 'XMAC-ORIT-GR',
                    ]),
                Forms\Components\TextInput::make('bottomDepth')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('topDepth')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('toolNo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('logQuality')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('bottomShotDepth')
                    ->maxLength(255),
                Forms\Components\TextInput::make('topShotDepth')
                    ->maxLength(255),
                Forms\Components\TextInput::make('charge')
                    ->maxLength(255),
                Forms\Components\TextInput::make('chargeNo')
                    ->maxLength(255),
                Forms\Components\TextInput::make('primaChord')
                    ->maxLength(255),
                Forms\Components\TextInput::make('primaChordQty')
                    ->maxLength(255),
                Forms\Components\TextInput::make('fuse')
                    ->maxLength(255),
                Forms\Components\TextInput::make('fuseNo')
                    ->maxLength(255),
                Forms\Components\Select::make('fMf')
                    ->options([
                        'F' => 'F',
                        'MF' => 'MF',
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('logRecorded')
            ->columns([
                Tables\Columns\TextColumn::make('runNo'),
                Tables\Columns\TextColumn::make('logRecorded'),
                Tables\Columns\TextColumn::make('bottomDepth'),
                Tables\Columns\TextColumn::make('topDepth'),
                Tables\Columns\TextColumn::make('topDepth'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
