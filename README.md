<a href="https://github.com/zvizvi/filament-relation-manager-repeater" class="filament-hidden">

![Filament Relation Manager Repeater](https://raw.githubusercontent.com/zvizvi/filament-relation-manager-repeater/main/.github/banner.jpg)

</a>

# Filament Relation Manager Repeater

[![Latest Version on Packagist](https://img.shields.io/packagist/v/zvizvi/relation-manager-repeater.svg?style=flat-square)](https://packagist.org/packages/zvizvi/relation-manager-repeater)
[![Total Downloads](https://img.shields.io/packagist/dt/zvizvi/relation-manager-repeater.svg?style=flat-square)](https://packagist.org/packages/zvizvi/relation-manager-repeater)

A Filament plugin that adds a Repeater form interface for editing relationship records in Filament's relation managers.  
This plugin allows you to edit multiple related records at once using a repeater component.

## Installation

```bash
composer require zvizvi/relation-manager-repeater
```

## Usage

Add the `RelationManagerRepeaterAction` to your relation manager's table actions:

```php
use Zvizvi\RelationManagerRepeater\Tables\RelationManagerRepeaterAction;

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
                RelationManagerRepeaterAction::make(),
            ]);
    }
}
```

## Advanced Configuration

Since `RelationManagerRepeaterAction` extends Filament's Action class, all standard Action configurations are available (label, modalWidth, modalHeading, icon, color, etc.).

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
            RelationManagerRepeaterAction::make()
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
RelationManagerRepeaterAction::make()
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

- For each record, it determines whether to:
   - Delete records that are no longer present
   - Update existing records with new data
   - Create new records that were added
- Shows a success notification

This approach provides a clean interface for managing multiple related records at once.

## Screenshots

- _Relation manager action button:_  
<img alt="Relation manager action button" src="https://github.com/user-attachments/assets/152f5946-4269-4d57-bc63-f7ede49d1bad" />

- _Action form editor:_  
<img alt="Action form editor" src="https://github.com/user-attachments/assets/155fb063-aacd-49b5-8aec-22099c88bf26" />

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [zvizvi](https://github.com/zvizvi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
