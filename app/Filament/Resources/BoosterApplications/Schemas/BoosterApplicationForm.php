<?php

namespace App\Filament\Resources\BoosterApplications\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BoosterApplicationForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('character_name')->disabled(),
                TextInput::make('realm')->disabled(),
                TextInput::make('class')->disabled(),
                TextInput::make('spec')->disabled(),
                TextInput::make('logs_url')->disabled(),
                Textarea::make('experience')->disabled()->columnSpanFull(),
                
                \Filament\Schemas\Components\Section::make('HQ Verification')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending Review',
                                'approved' => 'Approved (Promote User)',
                                'rejected' => 'Rejected'
                            ])
                            ->required(),
                        Textarea::make('staff_notes')
                            ->placeholder('Internal review notes...')
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
            ]);
    }
}
