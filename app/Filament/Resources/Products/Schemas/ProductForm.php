<?php

namespace App\Filament\Resources\Products\Schemas;

use Dom\Text;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Product details')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Basic Information')
                            ->icon(Heroicon::InformationCircle)
                            ->schema([
                                Section::make('Product Details')
                                    ->schema([
                                        TextInput::make('name')
                                            ->required(),
                                        TextInput::make('slug')
                                            ->unique(ignoreRecord: true)
                                            ->visible(fn(string $operation) => $operation === 'edit')
                                            ->required(),
                                        Select::make('category_id')
                                            ->relationship('category', 'name')
                                            ->preload()
                                            ->searchable()
                                            ->required()
                                            ->searchable()
                                            ->createOptionForm([
                                                TextInput::make('name')
                                                    ->required(),
                                                TextInput::make('slug')
                                                    ->unique(ignoreRecord: true)
                                                    ->readOnly()
                                                    ->visibleOn('edit'),
                                            ]),

                                        Select::make('brand_id')
                                            ->relationship('brand', 'name')
                                            ->preload()
                                            ->required()
                                            ->searchable()
                                            ->createOptionForm([
                                                TextInput::make('name')
                                                    ->required(),
                                                TextInput::make('slug')
                                                    ->visibleOn('edit')
                                                    ->readOnly()
                                                    ->unique(ignoreRecord: true)
                                                    ->required(),
                                            ]),
                                    ])->columns(2),
                                Section::make('Product Description')
                                    ->schema([
                                        Textarea::make('short_description')
                                            ->default(null)
                                            ->columnSpanFull(),
                                        RichEditor::make('description')
                                            ->default(null)
                                            ->columnSpanFull(),
                                    ])
                            ]),

                        Tab::make('Pricing & inventory')
                            ->icon(Heroicon::CurrencyDollar)

                            ->schema([
                                Section::make('Pricing ')
                                    ->schema([
                                        TextInput::make('sku')
                                            ->label('SKU')
                                            ->unique(ignoreRecord: true)
                                            ->helperText('Stock Keeping  Unit  - unique identifier')
                                            ->required(),
                                        TextInput::make('price')
                                            ->required()
                                            ->numeric()
                                            ->minValue(0)
                                            ->step(0.01)
                                            ->helperText('Selling Price')
                                            ->prefix('$'),
                                        TextInput::make('compare_price')
                                            ->numeric()
                                            ->default(null)
                                            ->helperText('Original price  to show discount') //50

                                            ->step(0.01)
                                            ->prefix('$'),
                                        TextInput::make('cost_price')
                                            ->numeric()
                                            ->default(null)
                                            ->step(0.01)
                                            ->helperText('cost from Supplier(for profit calculations)')

                                            ->prefix('$'),
                                    ])->columns(2),
                                Section::make('Inventory')
                                    ->schema([
                                        Toggle::make('manage_stock')
                                            ->default(true)
                                            ->helperText('Enable stock management for this product')
                                            ->live(),
                                        TextInput::make('stock_quantity')
                                            ->label('Stock Quantity')
                                            ->required(fn(callable $get) => $get('manage_stock'))
                                            ->disabled(fn(callable $get) => !$get('manage_stock'))
                                            ->numeric()
                                            ->default(0),
                                        TextInput::make('low_stock_threshold')
                                            ->label('Low stock Alert Threshold')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->helperText('Get notified when stock falls below this number'),
                                        Select::make('stock_status')
                                            ->options([
                                                'in_stock' => 'In Stock',
                                                'out_of_stock' => 'Out of Stock',
                                                'on_backorder' => 'On Backorder',
                                            ])
                                            ->native(false)
                                            ->default('in_stock')
                                            ->required(),
                                        TextInput::make('weight')
                                            ->label('Weight (Kg)')
                                            ->numeric()
                                            ->minValue('0')
                                            ->helperText('Used for Shipping calculations')
                                            ->default(null),
                                    ])
                                    ->columns(2)
                            ]),
                        Tab::make('Images')
                            ->icon(Heroicon::Photo)
                            ->schema([
                                Section::make('Product Images ')
                                    ->description('Upload Multiple images. the first image will be the primary image.')
                                    ->schema([
                                        FileUpload::make('images')
                                            ->label('Product Images')
                                            ->multiple()
                                            ->image()
                                            ->directory('products')
                                            ->imageEditor()
                                            ->maxSize(2048)
                                            ->reorderable()
                                            ->columnSpanFull()
                                            ->helperText('You can drag and drop to reorder images')
                                            ->saveRelationshipsUsing(function ($compoent, $state, $record) {
                                                $record->images()->delete();
                                                if (is_array($state)) {
                                                    foreach ($state as $index => $imagePath) {
                                                        $record->images()->create([
                                                            'image_path' => $imagePath,
                                                            'is_primary' => $index === 0,
                                                            'sort_order' => $index
                                                        ]);
                                                    }
                                                }
                                            })
                                            ->dehydrated(false)
                                    ])
                            ]),
                    ]),

                Tab::make('Setting')
                    ->icon(Heroicon::Cog6Tooth)
                    ->schema([
                        Section::make('Prodcut status')
                            ->schema([
                                Toggle::make('is_active')
                                    ->required(),

                                Toggle::make('is_featured')
                                    ->required(),
                            ])
                            ->columns(2),
                    ]),






                Toggle::make('has_variants')
                    ->required(),

                TextInput::make('meta_title'),
                Textarea::make('meta_description')
                    ->columnSpanFull(),
                TextInput::make('views_count')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
