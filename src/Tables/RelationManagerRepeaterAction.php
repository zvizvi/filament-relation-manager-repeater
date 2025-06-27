<?php

namespace Zvizvi\RelationManagerRepeater\Tables;

use Closure;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Str;
use ReflectionClass;

/**
 * Action for editing relationship records in Filament
 *
 * This action creates a repeater interface for editing multiple related records at once.
 * It can be customized with a schema and additional repeater configurations.
 */
class RelationManagerRepeaterAction extends Action
{
    /**
     * Optional closure to configure the repeater component
     */
    protected ?Closure $repeaterConfigurationClosure = null;

    /**
     * Create a new edit relationship action
     *
     * @param string|null $name The name of the action
     * @return static
     */
    public static function make(?string $name = null): static
    {
        $action = parent::make($name ?? 'edit-relationship');

        return $action->configureAction();
    }

    /**
     * Configure the repeater component with a custom closure
     *
     * @param Closure $closure A closure that receives the Repeater instance and returns the configured Repeater
     * @return $this
     */
    public function configureRepeater(Closure $closure): static
    {
        $this->repeaterConfigurationClosure = $closure;

        return $this;
    }

    /**
     * Configure the action with all necessary callbacks
     *
     * @return $this
     */
    protected function configureAction(): static
    {
        return $this
            ->label($this->getLabelCallback())
            ->fillForm($this->getFillFormCallback())
            ->form($this->getFormCallback())
            ->modalSubmitActionLabel(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
            ->action($this->getActionCallback())
            ->hidden(fn(RelationManager $livewire): bool => $livewire->isReadOnly());
    }

    /**
     * Get the callback for the action label
     *
     * @return Closure
     */
    protected function getLabelCallback(): Closure
    {
        return function (RelationManager $livewire): string {
            $pluralModelLabel = self::getRelationManagerPluralLabel($livewire);
            return __('Edit') . ' ' . $pluralModelLabel;
        };
    }

    /**
     * Get the callback for the form
     *
     * @return Closure
     */
    protected function getFormCallback(): Closure
    {
        return function (Form $form, RelationManager $livewire): Form {
            $relationshipName = $livewire->getRelationshipName();
            $pluralModelLabel = self::getRelationManagerPluralLabel($livewire);
            $formInstance = $livewire->form($form);

            $repeater = $this->buildRepeater($relationshipName, $pluralModelLabel, $formInstance);

            return $formInstance->schema([$repeater]);
        };
    }

    /**
     * Build the repeater component with all configurations
     *
     * @param string $relationshipName The name of the relationship
     * @param string $pluralModelLabel The plural label for the model
     * @param Form $formInstance The form instance
     * @return Repeater
     */
    protected function buildRepeater(string $relationshipName, string $pluralModelLabel, Form $formInstance): Repeater
    {
        $repeater = Repeater::make($relationshipName)
            ->label($pluralModelLabel)
            ->reorderable(false)
            ->schema($formInstance->getComponents())
            ->columns(columns: $formInstance->getColumns())
            ->columnSpanFull()
            ->defaultItems(1);

        if ($this->repeaterConfigurationClosure) {
            $repeater = call_user_func($this->repeaterConfigurationClosure, $repeater);
        }

        return $repeater;
    }

    /**
     * Get the callback for mounting the form
     *
     * @return Closure
     */
    protected function getFillFormCallback(): Closure
    {
        return function (RelationManager $livewire): array|null {
            $relationshipName = $livewire->getRelationshipName();
            $relationshipData = $livewire->getOwnerRecord()->{$relationshipName}()->get()->toArray();

            if (empty($relationshipData)) {
                return null;
            }
            return [$relationshipName => $relationshipData];
        };
    }

    /**
     * Get the callback for the action
     *
     * @return Closure
     */
    protected function getActionCallback(): Closure
    {
        return function (array $data, RelationManager $livewire): void {
            $relationshipName = $livewire->getRelationshipName();
            $relationship = $livewire->getOwnerRecord()->{$relationshipName}();
            $newData = collect($data[$relationshipName]);

            // Delete removed records
            $relationship->whereNotIn('id', $newData->pluck('id')->filter())->delete();

            // Update or create records
            foreach ($newData as $item) {
                if (!empty($item['id'])) {
                    if ($record = $relationship->getModel()->find($item['id'])) {
                        $data = collect($item)->except('id');
                        $record->fill($data->toArray())->save();
                    }
                } else {
                    $relationship->create(collect($item)->except('id')->toArray());
                }
            }

            Notification::make()
                ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
                ->success()
                ->send();
        };
    }

    /**
     * Get the plural model label from the relation manager
     *
     * @param RelationManager $relationManager The relation manager instance
     * @return string
     */
    private static function getRelationManagerPluralLabel(RelationManager $relationManager): string
    {
        $relationManagerClass = get_class($relationManager);

        $relationshipName = $relationManager->getRelationshipName();
        $pluralRelationshipName = Str::plural($relationshipName);

        try {
            $reflection = new ReflectionClass($relationManagerClass);
            $pluralModelLabel = $reflection->getMethod('getPluralModelLabel')->invoke($relationManager) ??
                $reflection->getMethod('getTitle')->invoke($relationManager, $relationManager->getOwnerRecord(), $pluralRelationshipName) ??
                $pluralRelationshipName;
        } catch (\Exception $e) {
            $pluralModelLabel = $pluralRelationshipName;
        }

        return $pluralModelLabel;
    }
}
