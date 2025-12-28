<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Information')
                    ->Schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->unique(ignoreRecord:true)
                            ->email()
                            ->required(),
                        DateTimePicker::make('email_verified_at'),

                        TextInput::make('phone')
                            ->tel(),
                        DatePicker::make('date_of_birth')
                         ->native(false)
                        ->native()
                        ->displayFormat('M , D , Y'),
                        Select::make('gender')
                            ->options(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'])
                            ->default(null)
                            ->native(false),
                        Toggle::make('is_active')
                            ->required(),
                    ])
                    ->columns(2),
                    Section::make('Password Information')
                    ->schema([
                         TextInput::make('password')
                            ->password()
                       ->dehydrateStateUsing(fn($state)=>filled($state) ? Hash::make($state): null)

                            ->dehydrated(fn($state)=>filled($state))
                            ->required(fn(string $operation)=>$operation ==='create')
                            ->required(),
                              TextInput::make('password Confirmation')
                            ->password()
                            ->same('password')
                            ->revealable()
                            ->dehydrated(false)
                            ->required(fn(string $operation)=>$operation ==='create')

                    ]),

            ]);
    }
}
