<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Str;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\View\View;
use \App\Enums\ModelPageType;

class AdminController extends Controller
{
    /**
     * manageable_model_browse
     *
     * @param  mixed $request
     * @param  mixed $table
     * @return View | RedirectResponse
     */
    public function manageableModelBrowse(Request $request, string $table): View | RedirectResponse
    {
        // Get this model's class
        $modelClass = $this->getModelFromTable($table);

        // Get instance of this model
        $model = new $modelClass();

        // Check permissions and redirect if not allowed
        if ($model->isViewable() == false) {
            return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to view '.$model->getHumanName());
        }

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
            'rows' => $rows
        ]);
    }

    /**
     * manageableModelCreate
     *
     * @param  mixed $request
     * @param  mixed $table
     * @return View | RedirectResponse
     */
    public function manageableModelCreate(Request $request, string $table): View | RedirectResponse
    {
        // Get this model's class
        $modelClass = $this->getModelFromTable($table);

        // Get instance of this model
        $model = new $modelClass();

        // Check permissions and redirect if not allowed
        if ($model->isCreatable() == false) {
            return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to create '.$model->getHumanName());
        }

        // Get manageable fields
        $fields = $model->getManageableFields(ModelPageType::Create);

        // Pass manageable fields to view
        return view('admin.manageable-models.create', [
            'model' => $model,
            'fields' => $fields,
        ]);
    }

    /**
     * manageableModelEdit
     *
     * @param  mixed $request
     * @param  mixed $table
     * @param  mixed $id
     * @return View | RedirectResponse
     */
    public function manageableModelEdit(Request $request, string $table, int $id): View | RedirectResponse
    {
        // Get this model's class
        $modelClass = $this->getModelFromTable($table);

        // Get instance of this model by id
        $model = $modelClass::find($id);

        // Check permissions and redirect if not allowed
        if ($model->isEditable() == false) {
            return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to edit '.$model->getHumanName());
        }

        // Get manageable fields
        $fields = $model->getManageableFields(ModelPageType::Edit);

        // Pass manageable fields to view
        return view('admin.manageable-models.edit', [
            'model' => $model,
            'fields' => $fields
        ]);
    }

    /**
     * manageableModelCreateSubmit
     *
     * @param  mixed $request
     * @param  mixed $table
     * @return View | RedirectResponse
     */
    public function manageableModelCreateSubmit(Request $request, string $table): View | RedirectResponse
    {
        // Get this model's class
        $modelClass = $this->getModelFromTable($table);

        // Get instance of this model
        $model = new $modelClass();

        // Check permissions and redirect if not allowed
        if ($model->isCreatable() == false) {
            return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to create '.$model->getHumanName());
        }

        // Run onCreateHook
        $request = $model->onCreateHook($request);

        // Run model validation
        $model->validate($request, ModelPageType::Create);

        // Get manageable fields
        $fields = $model->getManageableFields('create');

        // Loop through fields
        foreach ($fields as $field) {
            // Check if field is manageable field object
            if (!($field instanceof \App\Classes\ManageableFields\ManageableField)) {
                continue;
            }

            // Get field name
            $fieldName = $field->name;

            // Check if attribute exists on model schema
            if(\Schema::hasColumn($model->getTable(), $fieldName) == false) {
                dump('Field '.$fieldName.' does not exist on table '.$model->getTable());
                continue;
            }

            // Get field value
            $fieldValue = $request->get($fieldName);

            // If field value is null we set to an empty string
            if ($fieldValue == null) {
                $fieldValue = '';
            }

            // Set field value
            $model->$fieldName = $fieldValue;
        }

        // Save model
        $model->save();

        // Redirect to edit
        return redirect()->route('admin.manageable-models.edit', ['table' => $table, 'id' => $model->id])->with('success', $model->getHumanName(false).' created successfully');
    }

    /**
     * manageableModelEditSubmit
     *
     * @param  mixed $request
     * @param  mixed $table
     * @param  mixed $id
     * @return View | RedirectResponse
     */
    public function manageableModelEditSubmit(Request $request, string $table, int $id): View | RedirectResponse
    {
        // Get this model's class
        $modelClass = $this->getModelFromTable($table);

        // Get instance of this model by id
        $model = $modelClass::find($id);

        // Check permissions and redirect if not allowed
        if ($model->isEditable() == false) {
            return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to edit '.$model->getHumanName());
        }

        // Run onEditHook
        $request = $model->onEditHook($request);

        // Run model validation
        $model->validate($request, ModelPageType::Edit);

        // Get manageable fields
        $fields = $model->getManageableFields(ModelPageType::Edit);

        // Loop through fields
        foreach ($fields as $field) {
            // Check if field is manageable field object
            if (!($field instanceof \App\Classes\ManageableFields\ManageableField)) {
                continue;
            }

            // Get field name
            $fieldName = $field->name;

            // Check if attribute exists on model table
            if(\Schema::hasColumn($model->getTable(), $fieldName) == false) {
                dump('Field '.$fieldName.' does not exist on table '.$model->getTable());
                continue;
            }

            // Get field value
            $fieldValue = $request->get($fieldName);

            // If field value is null we set to an empty string
            if ($fieldValue == null) {
                $fieldValue = '';
            }

            // Set field value
            $model->$fieldName = $fieldValue;
        }

        // Save model
        $model->save();

        // Redirect to browse
        return redirect()->route('admin.manageable-models.edit', ['table' => $table, 'id' => $id])->with('success', $model->getHumanName(false).' updated successfully');
    }

    /**
     * manageableModelDeleteSubmit
     *
     * @param  mixed $request
     * @param  mixed $table
     * @param  mixed $id
     * @return View | RedirectResponse
     */
    public function manageableModelDeleteSubmit(Request $request, string $table, int $id): View | RedirectResponse
    {
        // Get this model's class
        $modelClass = $this->getModelFromTable($table);

        // Get instance of this model by id
        $model = $modelClass::find($id);

        // Check permissions and redirect if not allowed
        if ($model->isDeletable() == false) {
            return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to delete '.$model->getHumanName());
        }

        // Run onDeleteHook
        $request = $model->onDeleteHook($request);

        // Delete model
        $model->delete();

        // Redirect to browse
        return redirect()->route('admin.manageable-models.browse', ['table' => $table])->with('success', $model->getHumanName(false).' #'.$id.' deleted successfully');
    }

    /**
     * loginAsUser
     *
     * @param  mixed $request
     * @param  mixed $userid
     * @return RedirectResponse
     */
    public function loginAsUser(Request $request, int $userid)
    {
        // Check if master, if not then fail and redirect
        if (Auth::user()->isMaster() == false) {
            return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to do this');
        }

        // Get user
        $user = User::find($userid);

        // Check if user exists
        if ($user == null) {
            return redirect()->route('admin.dashboard')->with('error', 'User does not exist');
        }

        // Login as user
        Auth::login($user);

        // Redirect to dashboard
        return redirect()->route('admin.dashboard')->with('success', 'Logged in as '.$user->name);
    }

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
