<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JcrResource\Pages;
use App\Filament\Resources\JcrResource\RelationManagers;
use App\Filament\Resources\JcrResource\RelationManagers\ExplosivesRelationManager;
use App\Filament\Resources\JcrResource\RelationManagers\LogsRelationManager;
use App\Filament\Resources\JcrResource\RelationManagers\UsersRelationManager;
use App\Filament\Resources\JcrResource\RelationManagers\ChecklistsRelationManager;
use App\Filament\Resources\JcrResource\RelationManagers\TimeRegisterRelationManager;
use App\Models\Jcr;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\User;
use Filament\Forms\Components\Wizard;

class JcrResource extends Resource
{
    protected static ?string $model = Jcr::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Basic Info')
                        ->columns(2)
                        ->schema([
                        Forms\Components\TextInput::make('fieldName')
                            ->required(),
                        Forms\Components\TextInput::make('wellNo')
                            ->required(),
                        Forms\Components\DatePicker::make('jobDate')
                            ->required(),
                        Forms\Components\TextInput::make('jobNo')
                            ->required()
                            ->numeric(),
                        Forms\Components\DatePicker::make('workOrderDate')
                            ->required(),
                        Forms\Components\TextInput::make('indentNo')
                            ->required(),
                        Forms\Components\TextInput::make('rigNo')
                            ->required(),
                        Forms\Components\TextInput::make('kb')
                            ->numeric(),
                        Forms\Components\TextInput::make('gl')
                            ->numeric(),
                        Forms\Components\TextInput::make('unitNo')
                            ->required(),
                        Forms\Components\TextInput::make('loggingType')
                            ->required(),
                        Forms\Components\TextInput::make('logType')
                            ->required(),
                        Forms\Components\TextInput::make('wellOwner')
                            ->required(),
                        Forms\Components\TextInput::make('mastVanNo'),
                        Forms\Components\TextInput::make('lvNo')
                            ->required(),
                        Forms\Components\TextInput::make('wellType')
                            ->required(),
                        Forms\Components\TextInput::make('rigType')
                            ->required(),
                    ]),
                    Wizard\Step::make('Time Info')
                        ->columns(2)
                        ->schema([
                        Forms\Components\DatePicker::make('assembled_date')
                            ->date()
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\TimePicker::make('assembled_time')
                            ->seconds(false)
                            ->native(false)
                            ->displayFormat('H:i')
                            ->format('H:i')
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\DatePicker::make('depOffice_date')
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\TimePicker::make('depOffice_time')
                            ->seconds(false)
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\DatePicker::make('arrivalSite_date')
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\TimePicker::make('arrivalSite_time')
                            ->seconds(false)
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\DatePicker::make('indented_date')
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\TimePicker::make('indented_time')
                            ->seconds(false)
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\DatePicker::make('wellReadiness_date')
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\TimePicker::make('wellReadiness_time')
                            ->seconds(false)
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\DatePicker::make('wellTaken_date')
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\TimePicker::make('wellTaken_time')
                            ->seconds(false)
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\DatePicker::make('rigUP_date')
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\TimePicker::make('rigUP_time')
                            ->seconds(false)
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\DatePicker::make('wellHandOver_date')
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\TimePicker::make('wellHandOver_time')
                            ->seconds(false)
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\DatePicker::make('depSite_date')
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\TimePicker::make('depSite_time')
                            ->seconds(false)
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\DatePicker::make('arrivalOffice_date')
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\TimePicker::make('arrivalOffice_time')
                            ->seconds(false)
                            ->required()
                            ->timezone('GMT+5:23')
                            ->native(false),
                        Forms\Components\TextInput::make('preparationTime')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('postProceTime')
                            ->required()
                            ->numeric(),
                    ]),
                    Wizard\Step::make('Well Info')
                        ->columns(2)
                        ->schema([
                            Section::make('Well Info')
                                ->columns(2)
                                ->schema([
                                    Forms\Components\TextInput::make('depthDriller')->numeric(),
                                    Forms\Components\TextInput::make('depthLogger')->numeric(),
                                    Forms\Components\TextInput::make('casingSize'),
                                    Forms\Components\TextInput::make('casingShoeDriller')->numeric(),
                                    Forms\Components\TextInput::make('casingShoeLogger')->numeric(),
                                    Forms\Components\TextInput::make('floatCollar')->numeric(),
                                    Forms\Components\TextInput::make('bitSize'),
                                    Forms\Components\TextInput::make('tubingSize'),
                                    Forms\Components\TextInput::make('t_shoe_Packer'),
                                    Forms\Components\TextInput::make('s_nippletopexp'),
                                    Forms\Components\TextInput::make('THP'),
                                    Forms\Components\TextInput::make('maxDevAt'),
                                    Forms\Components\TextInput::make('distTo_FroKms'),
                                ])
                                ->columnSpan(1),
                                Section::make('Mud Info')
                                ->columns(2)
                                ->schema([
                                    Forms\Components\TextInput::make('rm')->numeric(),
                                    Forms\Components\TextInput::make('rmtemp')->numeric(),
                                    Forms\Components\TextInput::make('rmf')->numeric(),
                                    Forms\Components\TextInput::make('rmftemp')->numeric(),
                                    Forms\Components\TextInput::make('rmc')->numeric(),
                                    Forms\Components\TextInput::make('rmctemp')->numeric(),
                                    Forms\Components\TextInput::make('bht')->numeric(),
                                    Forms\Components\TextInput::make('bhtdepth')->numeric(),
                                    Forms\Components\TextInput::make('spgr')->numeric(),
                                    Forms\Components\TextInput::make('viscosity')->numeric(),
                                    Forms\Components\TextInput::make('mudType'),
                                    Forms\Components\TextInput::make('waterloss')->numeric(),
                                    Forms\Components\TextInput::make('ph')->numeric(),
                                    Forms\Components\TextInput::make('oilpercnt')->numeric(),
                                    Forms\Components\TextInput::make('kcl_barytes')->numeric(),
                                    Forms\Components\TextInput::make('salinity')->numeric(),
                                    Forms\Components\TextInput::make('lastcirc_from'),
                                    Forms\Components\TextInput::make('lastcirc_to'),
                                ])
                                ->columnSpan(1),
                    ]),
                    Wizard\Step::make('Cable Details')
                        ->columns(2)
                        ->schema([
                            Section::make('Cable Details')
                                ->columns(1)
                                ->schema([
                                    Forms\Components\TextInput::make('cableSize')
                                        ->required(),
                                    Forms\Components\TextInput::make('insulation')
                                        ->required(),
                                    Forms\Components\DatePicker::make('shoeDate')
                                        ->required(),
                                    Forms\Components\TextInput::make('weakPoint')
                                        ->required(),
                                    Forms\Components\TextInput::make('cableHeadSize')
                                        ->required(),
                                    Forms\Components\TextInput::make('cableLength')
                                        ->numeric()
                                        ->required(),
                                    Forms\Components\TextInput::make('initialLength')
                                        ->numeric()
                                        ->required(),
                                ])
                                ->columnSpan(1),
                            Section::make('Equipment Details')
                                ->columns(1)
                                ->schema([
                                    Forms\Components\TextInput::make('surfaceEquipment')
                                        ->required(),
                                    Forms\Components\TextInput::make('automobile')
                                        ->required(),
                                    Forms\Components\TextInput::make('wellCondition')
                                        ->required(),
                                    Forms\Components\TextInput::make('timeLoss')
                                        ->required(),
                                ])
                                ->columnSpan(1),
                    ]),
                    Wizard\Step::make('SWC')
                        ->columns(1)
                        ->schema([
                            Forms\Components\TextInput::make('attempted')
                                ->numeric(),
                            Forms\Components\TextInput::make('recovered')
                                ->numeric(),
                            Forms\Components\TextInput::make('missFire')
                                ->numeric(),
                            Forms\Components\TextInput::make('barrelLost')
                                ->numeric(),
                            Forms\Components\TextInput::make('emptyBarrel')
                                ->numeric(),
                            Forms\Components\TextInput::make('chargeUsed')
                                ->numeric(),
                    ]),
                    Wizard\Step::make('Safety Info')
                        ->columns(1)
                        ->schema([
                            Forms\Components\TextInput::make('permitType')
                                ->required(),
                            Forms\Components\TextInput::make('permitNo')
                                ->required(),
                            Forms\Components\TextInput::make('permitWork')
                                ->required()
                                ->numeric(),
                            Forms\Components\TextInput::make('elecLockout')
                                ->numeric(),
                            Forms\Components\TextInput::make('elecLockoutNo'),
                            Forms\Components\TextInput::make('safetyMeeting')
                                ->required()
                                ->numeric(),
                            Forms\Components\TextInput::make('jobCloseMeeting')
                                ->required()
                                ->numeric(),
                            Forms\Components\TextInput::make('nearMiss')
                                ->required()
                                ->numeric(),
                            Forms\Components\TextInput::make('nearMissDesc'),
                        ]),
                    Wizard\Step::make('Final Info')
                        ->columns(1)
                        ->schema([
                            Forms\Components\TextInput::make('jobStatus')
                                ->required(),
                            Forms\Components\TextInput::make('remarks')
                                ->required(),
                            Forms\Components\TextInput::make('objective'),
                            Forms\Components\TextInput::make('observations'),
                            Forms\Components\TextInput::make('contingents')
                                ->required(),
                            Forms\Components\TextInput::make('final_submit')
                                ->numeric(),
                            Forms\Components\TextInput::make('created_at'),
                            Forms\Components\TextInput::make('final_submit'),
                            Forms\Components\TextInput::make('creator_id'),
                            Forms\Components\TextInput::make('creator_signature'),
                            Forms\Components\TextInput::make('creator_signed_at'),
                            Forms\Components\TextInput::make('party_chief_edited'),
                            Forms\Components\TextInput::make('party_chief_id'),
                            Forms\Components\TextInput::make('party_chief_signature'),
                            Forms\Components\TextInput::make('party_chief_signed_at'),
                            Forms\Components\TextInput::make('operation_incharge_edited'),
                            Forms\Components\TextInput::make('operation_incharge_id'),
                            Forms\Components\TextInput::make('operation_incharge_signature'),
                            Forms\Components\TextInput::make('operation_incharge_signed_at'),
                            Forms\Components\TextInput::make('status'),
                    ]),
                ])
                ->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('wellNo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jobDate')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('indentNo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unitNo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assembled_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assembled_time')
                ->dateTime('H:i'),
                Tables\Columns\TextColumn::make('arrivalOffice_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('arrivalOffice_time')
                ->dateTime('H:i'),
                Tables\Columns\TextColumn::make('contingents')
                    ->searchable(),
                Tables\Columns\TextColumn::make('final_submit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultsort(function (Builder $query): Builder {
            return $query->orderBy('arrivalOffice_date', 'desc')
                        ->orderBy('arrivalOffice_time', 'desc');
                    })
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
            UsersRelationManager::class,
            ExplosivesRelationManager::class,
            LogsRelationManager::class,
            ChecklistsRelationManager::class,
            TimeRegisterRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['users', 'logs', 'explosives', 'creator', 'timeRegister']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJcrs::route('/'),
            'create' => Pages\CreateJcr::route('/create'),
            'view' => Pages\ViewJcr::route('/{record}'),
            'edit' => Pages\EditJcr::route('/{record}/edit'),
        ];
    }
}
