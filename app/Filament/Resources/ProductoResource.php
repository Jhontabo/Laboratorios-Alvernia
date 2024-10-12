<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoResource\Pages;
use App\Filament\Resources\ProductoResource\RelationManagers;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('descripcion'),
                TextInput::make('numero_serie')
                    ->label('Número de serie'),
                TextInput::make('cantidad_disponible')
                    ->numeric(),
                TextInput::make('ubicacion'),
                Select::make('Estado')
                    ->options([
                        'nuevo' => 'Nuevo',
                        'usado' => 'Usado',
                        'dañado' => 'Dañado',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre'),
                TextColumn::make('cantidad_disponible'),
                BadgeColumn::make('estado')
                    ->label('Estado')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'nuevo' => 'Nuevo',
                            'usado' => 'Usado',
                            'dañado' => 'Dañado',
                            default => '-',
                        };
                    })
                    ->colors([
                        'success' => 'nuevo',
                        'warning' => 'usado',
                        'danger' => 'dañado',
                    ]),
                TextColumn::make('ubicacion'),
                TextColumn::make('numero_serie'),
                TextColumn::make('fecha_adicion')->date(),
            ])
            ->filters([
                // Agrega filtros si es necesario
            ])
            ->actions([
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
            'index' => Pages\ListProductos::route('/'),
            'create' => Pages\CreateProducto::route('/create'),
            'edit' => Pages\EditProducto::route('/{record}/edit'),
        ];
    }
}
