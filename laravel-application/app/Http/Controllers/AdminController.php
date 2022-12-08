<?php

namespace App\Http\Controllers;

use Str;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\View\View;

class AdminController extends Controller
{
    /**
     * manageable_model_browse
     *
     * @param  mixed $request
     * @param  mixed $table
     * @return View
     */
    public function manageable_model_browse(Request $request, string $table): View
    {
        // Get this model's class
        $modelClass = $this->getModelFromTable($table);

        // Get instance of this model
        $model = new $modelClass();

        // Get browseable columns
        $columns = $model->getBrowsableColumns();

        // Get this model's rows
        $rows = $model->all();

        // Remove id and password columns
        $columns = array_diff($columns, ['id', 'password']);

        // Return view
        return view('admin.manageable-models.browse', [
            'model' => $model,
            'columns' => $columns,
            'rows' => $rows,
        ]);
    }

    // TODO: Add manageable_model_add

    // TODO: Add manageable_model_edit

    // TODO: Add manageable_model_delete

    /**
     * getModelFromTable
     * Returns a string of the fully qualified model class name from the table name
     * @param  mixed $table
     * @return string
     */
    private function getModelFromTable(string $table): string
    {
        return 'App\Models\\'.Str::studly(Str::singular($table));
    }
}
