<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Tenant;
use App\Models\User;
use BackedEnum;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use PhpParser\NodeVisitor\NameResolver;
use Closure;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-user';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('cpf')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('seniority')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('designation'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->tel(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull()
                    ->hint(fn($state, $component) => 'Left: ' . $component->getMaxLength() - strlen($state) . ' characters')
                    ->maxlength(255)
                    ->lazy(),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ])
                    ->default(1),
                Forms\Components\Select::make('roles')
                    ->required()
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'),
                Forms\Components\Section::make('Approval Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_approved')
                            ->label('Approved')
                            ->helperText('Toggle to approve or reject user access'),
                        Forms\Components\DateTimePicker::make('approved_at')
                            ->label('Approved At')
                            ->disabled()
                            ->nullable(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Location')
                    ->description('Assign this user to a tenant location. Super-admins can log in from any location.')
                    ->schema([
                        Forms\Components\Select::make('tenant_id')
                            ->label('Assigned Location')
                            ->placeholder('Select a location...')
                            ->options(function () {
                                // Always query the central DB regardless of current tenant context.
                                return Tenant::on('central')
                                    ->get()
                                    ->mapWithKeys(fn ($t) => [
                                        $t->id => ucfirst($t->id),
                                    ]);
                            })
                            ->searchable()
                            ->nullable()
                            ->helperText('The subdomain the user must log in from (e.g. ankleshwar.localhost).'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('seniority')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cpf')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('designation')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tenant_id')
                    ->label('Location')
                    ->formatStateUsing(fn ($state) => $state ? ucfirst($state) : '—')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_approved')
                    ->boolean()
                    ->label('Approved'),
                Tables\Columns\TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Approved At'),
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
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Approve')
                        ->action(fn (Collection $records) => $records->each(fn ($record) => $record->update([
                            'is_approved' => true,
                            'approved_at' => now(),
                        ])))
                        ->requiresConfirmation()
                        ->icon('heroicon-o-check-circle')
                        ->color('success'),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\UserResource\RelationManagers\TimeRegistersRelationManager::class,
            \App\Filament\Resources\UserResource\RelationManagers\JcrsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
