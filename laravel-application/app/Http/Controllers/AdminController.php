<?php

namespace App\Http\Controllers;

use Str;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\View\View;

class AdminController extends Controller
{
    public function manageable_model_browse(Request $request, string $table): View
    {
        // Get model name from table name
        $model = Str::studly(Str::singular($table));

        // Get this model's class
        $modelClass = 'App\Models\\' . $model;

        // Get instance of this model
        $model = new $modelClass();

        // Get all columns
        $columns = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());

        // Get this model's rows
        $rows = $model->all();

        // Remove id and password columns
        $columns = array_diff($columns, ['id', 'password']);

        // Return view
        return view('admin.manageable-models.browse', [
            'model' => $model,
            'rows' => $rows,
            'columns' => $columns,
        ]);
    }
}
