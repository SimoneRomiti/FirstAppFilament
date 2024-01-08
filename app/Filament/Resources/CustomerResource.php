<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Axiostudio\Comuni\Models\Zip;
use Axiostudio\Comuni\Models\City;
use Filament\Forms\Components\Select;
use Axiostudio\Comuni\Models\Province;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CustomerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CustomerResource\RelationManagers;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                TextInput::make('name'),
                TextInput::make('surname'),
                TextInput::make('company')->columnSpanFull()->required(),
                TextInput::make('address')->columnSpanFull()->required(),
                Select::make('province_id')
                    ->label('Province')
                    ->afterStateUpdated(function ($get, $set) {
                        $set('city_id', null);
                        $set('zipcode_id', null);
                    })
                    ->options(Province::orderBy('name')->pluck('name', 'id'))
                    ->live(),
                Select::make('city_id')
                    ->label('City')
                    ->options(function($get, $set){
                        if($get('province_id')){
                            return City::orderBy('name')->where('province_id', $get('province_id'))->pluck('name', 'id');
                        } else {
                            return [];
                        }
                    })
                    ->live(),
                Select::make('zip_id')
                    ->label('Zip')
                    ->options(function($get, $set){
                        if($get('city_id')){
                            return Zip::orderBy('code')->where('city_id', $get('city_id'))->pluck('code', 'id');
                        } else {
                            return [];
                        }
                    })
                    ->live()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company'),
                TextColumn::make('name'),
                TextColumn::make('surname'),
                TextColumn::make('province.name'),
                TextColumn::make('city.name'),
                TextColumn::make('address'),
                TextColumn::make('zip.code'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
