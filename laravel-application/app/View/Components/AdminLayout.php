<?php

namespace App\View\Components;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use ReflectionClass;

class AdminLayout extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // Get manageable models
        $manageableModels = $this->getManageableModels();

        $data = [
            'navigation' => config('admin-navigation'),
            'manageableModels' => $manageableModels
        ];

        return view('components.admin.layouts.layout', $data);
    }

    /**
     * Get manageable models
     *
     * @return Collection
     */
    private function getManageableModels(): Collection
    {
        // Get all classes that use the ManageableModel trait
        $manageableModels = new Collection();

        // Get all models (by filename)
        $models = array_diff(scandir(app_path('Models')), ['..', '.']);

        // Filter all models that use the ManageableModel trait
        foreach ($models as $model) {
            $model = substr($model, 0, -4);

            // Check if the model uses the ManageableModel trait
            if (in_array('App\Traits\ManageableModel', class_uses('App\Models\\' . $model))) {
                // Get an instance of the model
                $modelReference = 'App\Models\\' . $model;
                $modelInstance = $modelReference::getNewInstance();

                // If the model is viewable, add it to the collection
                if ($modelInstance->isViewable()) {
                    $manageableModels->add($modelReference);
                }
            }
        }

        return $manageableModels;
    }
}
