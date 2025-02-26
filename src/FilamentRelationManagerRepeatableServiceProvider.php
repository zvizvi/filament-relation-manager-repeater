<?php

namespace Zvizvi\FilamentRelationManagerRepeatable;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Zvizvi\FilamentRelationManagerRepeatable\Commands\FilamentRelationManagerRepeatableCommand;
use Zvizvi\FilamentRelationManagerRepeatable\Testing\TestsFilamentRelationManagerRepeatable;

class FilamentRelationManagerRepeatableServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-relation-manager-repeatable';

    public static string $viewNamespace = 'filament-relation-manager-repeatable';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('zvizvi/filament-relation-manager-repeatable');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-relation-manager-repeatable/{$file->getFilename()}"),
                ], 'filament-relation-manager-repeatable-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentRelationManagerRepeatable);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'zvizvi/filament-relation-manager-repeatable';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-relation-manager-repeatable', __DIR__ . '/../resources/dist/components/filament-relation-manager-repeatable.js'),
            Css::make('filament-relation-manager-repeatable-styles', __DIR__ . '/../resources/dist/filament-relation-manager-repeatable.css'),
            Js::make('filament-relation-manager-repeatable-scripts', __DIR__ . '/../resources/dist/filament-relation-manager-repeatable.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentRelationManagerRepeatableCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_filament-relation-manager-repeatable_table',
        ];
    }
}
