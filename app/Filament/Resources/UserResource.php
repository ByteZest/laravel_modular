<?php

namespace App\Filament\Resources;

use App\Domain\Users\Models\User;
use App\Filament\Resources\UserResource\Pages;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                ->minLength(2)
                ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->email(),
                Forms\Components\TextInput::make('password')
                    ->required()
                    ->visibleOn('create')
                    ->password(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->size('sm')
                    ->copyable()
                    ->copyMessage('Copied ID to clipboard.')
                    ->copyMessageDuration(1500)
                    ->limit(12),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->icon(fn ($record) => $record->email_verified_at ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->iconPosition('after'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->size('sm')
                    ->date('M j, Y H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
