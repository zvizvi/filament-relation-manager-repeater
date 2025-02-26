# Filament Relation Manager Repeatable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/zvizvi/relation-manager-repeatable.svg?style=flat-square)](https://packagist.org/packages/zvizvi/relation-manager-repeatable)
[![Total Downloads](https://img.shields.io/packagist/dt/zvizvi/relation-manager-repeatable.svg?style=flat-square)](https://packagist.org/packages/zvizvi/relation-manager-repeatable)

A Filament plugin that adds a repeatable interface for editing relationship records in Filament's relation managers.  
This plugin allows you to edit multiple related records at once using a repeatable component.

## Installation

```bash
composer require zvizvi/relation-manager-repeatable
```

## Usage

Add the `RelationManagerRepeatableAction` to your relation manager's table actions:

```php
use Zvizvi\RelationManagerRepeatable\Tables\RelationManagerRepeatableAction;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->headerActions([
                RelationManagerRepeatableAction::make(),
            ]);
    }
}
```

## Advanced Configuration

Since `RelationManagerRepeatableAction` extends Filament's Action class, all standard Action configurations are available (label, modalWidth, modalHeading, icon, color, etc.).

You can also customize the repeater component using the `configureRepeater` method. All standard Filament repeater options are available (reorderable, collapsible, cloneable, grid, itemLabel, etc.):

```php
use Filament\Forms\Components\Repeater;

public function table(Table $table): Table
{
    return $table
        ->columns([
            //
        ])
        ->headerActions([
            RelationManagerRepeatableAction::make()
                ->modalWidth('5xl')
                ->modalHeading('Edit Related Records')
                ->configureRepeater(function (Repeater $repeater) {
                    return $repeater
                        ->reorderable()
                        ->collapsible()
                        ->cloneable()
                        ->defaultItems(0)
                        ->maxItems(5);
                }),
        ]);
}
```

## Form Customization

By default, the repeater uses the form schema defined in your relation manager. You can customize which fields are displayed in the repeater by providing a custom schema:

```php
RelationManagerRepeatableAction::make()
    ->configureRepeater(function (Repeater $repeater) {
        return $repeater
            ->schema([
                // Only include specific fields
                TextInput::make('title'),
                TextInput::make('slug'),
                Toggle::make('is_published'),
                // Other fields...
            ]);
    }),
```

This allows you to display only a subset of fields from your relation manager's form, or even add custom fields specifically for the repeater interface.

## How It Works

The plugin creates a modal with a repeater component that loads all related records. When you save the form:

1. It deletes all existing related records
2. Creates new records based on the data in the repeater
3. Shows a success notification

This approach provides a clean interface for managing multiple related records at once.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
