<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ManageableModel;
use Illuminate\Database\Eloquent\Model;
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
     * Returns the view from browsing the given table (model)
     * @param  mixed $request
     * @param  mixed $table
     * @return View | RedirectResponse
     */
    public function manageableModelBrowse(Request $request, string $table): View | RedirectResponse
    {
        // Get this manageable model's class
        $manageableModelClass = $this->getManageableModelFromTable($table);

        // Get an instance of this manageable model
        $model = $manageableModelClass::getNewInstance();

        // Check permissions and redirect if not allowed
        if ($model->isViewable() == false) {
            return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to view '.$model->getHumanName());
        }

        // Get browseable columns
        $columns = $model->getBrowsableColumns();

        // Get this model's rows after filtering
        $rows = $this->browseAndFilter($model, $request)->withQueryString();

        // Remove id and password columns
        $columns = array_diff($columns, ['id', 'password']);

        // Return view
        return view('admin.manageable-models.browse', [
            'model' => $model,
            'columns' => $columns,
            'rows' => $rows,
            'pageTitle' => $model->getPageTitle(ModelPageType::Browse),
        ]);
    }

    /**
     * Filters the given model's rows based on the request
     * @param Model $model
     * @param Request $request
     * @return mixed
     */
    private function browseAndFilter(Model $model, Request $request): mixed
    {
        // Get request search
        $search = $request->get('search');

        // Get request sort
        $sort = $request->get('sort') ?? 'id';

        // Get request order
        $order = $request->get('order') ?? 'asc';

        // Get query
        $query = $model::query();

        // Get searchable columns
        $searchableColumns = $model->getSearchableColumns();

        // If search is set then search all columns
        if ($search) {
            $query->where(function ($query) use ($searchableColumns, $search) {
                foreach ($searchableColumns as $column) {
                    $query->orWhere($column, 'like', '%'.$search.'%');
                }
            });
        }

        // Order by and paginate
        $paginatedRows = $query->orderBy($sort, $order)->paginate($model->paginateAmount());

        // Loop through the paginated rows (just the ones for this page) and get the manageable field browse values
        for($i = 0; $i < count($paginatedRows); $i++) {
            // Get manageable fields from this row
            $manageableFields = array_filter($paginatedRows[$i]->getManageableFields(ModelPageType::Browse), function ($field) {
                return $field instanceof ManageableField;
            });

            // Loop through the manageable fields
            foreach ($manageableFields as $manageableField) {
                // Get the browse value for this manageable field
                $paginatedRows[$i]->{$manageableField->name} = $manageableField->getBrowseValue();
            }
        }

        // Return
        return $paginatedRows;
    }

    /**
     * Returns the view for creating a new manageable model
     * @param  mixed $request
     * @param  mixed $table
     * @return View | RedirectResponse
     */
    public function manageableModelCreate(Request $request, string $table): View | RedirectResponse
    {
        // Get this manageable model's class
        $manageableModelClass = $this->getManageableModelFromTable($table);

        // Get instance of this manageable model
        $model = $manageableModelClass::getNewInstance();

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
            'pageTitle' => $model->getPageTitle(ModelPageType::Create),
            'submitRoute' => $model->getSubmitRoute(ModelPageType::Create),
            'submitText' => $model->getSubmitText(ModelPageType::Create),
            // Need to get the id of the new model post creation below
            // 'onSuccessRedirect' => route('admin.manageable-models.edit', ['table' => $table, 'id' => $model->id]),
            'onSuccessRedirect' => route('admin.manageable-models.browse', ['table' => $table]),
        ]);
    }

    /**
     * Returns the view for editing a manageable model
     * @param  mixed $request
     * @param  mixed $table
     * @param  mixed $id
     * @return View | RedirectResponse
     */
    public function manageableModelEdit(Request $request, string $table, int $id): View | RedirectResponse
    {
        // Get this model's class
        $manageableModelClass = $this->getManageableModelFromTable($table);

        // Get instance of this manageable model
        $model = $manageableModelClass::getInstance($id);

        // Check permissions and redirect if not allowed
        if ($model->isEditable() == false) {
            return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to edit '.$model->getHumanName());
        }

        // Get manageable fields
        $fields = $model->getManageableFields(ModelPageType::Edit);

        // Pass manageable fields to view
        return view('admin.manageable-models.edit', [
            'model' => $model,
            'fields' => $fields,
            'pageTitle' => $model->getPageTitle(ModelPageType::Edit),
            'submitRoute' => $model->getSubmitRoute(ModelPageType::Edit),
            'submitText' => $model->getSubmitText(ModelPageType::Edit),
            'onSuccessRedirect' => route('admin.manageable-models.edit', ['table' => $table, 'id' => $model->id]),
        ]);
    }

    /**
     * Handles the submission of a creating a manageable model
     * @param  mixed $request
     * @param  mixed $table
     * @return View | RedirectResponse
     */
    public function manageableModelCreateSubmit(Request $request, string $table): View | RedirectResponse
    {
        // Get this manageable model's class
        $manageableModelClass = $this->getManageableModelFromTable($table);

        // Get an instance of this model
        $model = $manageableModelClass::getNewInstance();

        // Check permissions and redirect if not allowed
        if ($model->isCreatable() == false) {
            return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to create '.$model->getHumanName());
        }

        // We remove the __validated__ field from the request if it exists
        $request->request->remove('__validated__');

        // Run model validation
        $request->validate($model->validationRules($request, ModelPageType::Create));

        // We add __validated___ which is used to confirm validation has been run
        $request->merge(['__validated__' => true]);

        // Get on_success_redirect and remove it from the request
        $onSuccessRedirect = $request->on_success_redirect;
        $request->request->remove('on_success_redirect');

        // Run onCreateHook
        $request = $model->onCreateHook($request);

        // Get manageable fields
        $fields = $model->getManageableFields(ModelPageType::Create);

        // Loop through and update fields
        $this->updateModelFieldsFromRequest($model, $fields, $request);

        // Save model
        $model->save();

        // Redirect
        return redirect($onSuccessRedirect)->with('success', $model->getHumanName(false).' created successfully');
    }

    /**
     * Handles the submission of a editing a manageable model
     * @param  mixed $request
     * @param  mixed $table
     * @param  mixed $id
     * @return View | RedirectResponse
     */
    public function manageableModelEditSubmit(Request $request, string $table, int $id): View | RedirectResponse
    {
        // Get this manageable model's class
        $manageableModelClass = $this->getManageableModelFromTable($table);

        // Get instance of this model by id
        $model = $manageableModelClass::getInstance($id);

        // Check permissions and redirect if not allowed
        if ($model->isEditable() == false) {
            return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to edit '.$model->getHumanName());
        }

        // We remove the __validated__ field from the request if it exists
        $request->request->remove('__validated__');

        // Run model validation
        $request->validate($model->validationRules($request, ModelPageType::Edit));

        // We add __validated___ which is used to confirm validation has been run
        $request->merge(['__validated__' => true]);

        // Get on_success_redirect and remove it from the request
        $onSuccessRedirect = $request->on_success_redirect;
        $request->request->remove('on_success_redirect');

        // Run onEditHook
        $request = $model->onEditHook($request);

        // Get manageable fields
        $fields = $model->getManageableFields(ModelPageType::Edit);

        // Loop through and update fields
        $this->updateModelFieldsFromRequest($model, $fields, $request);

        // Save model
        $model->save();

        // Redirect
        return redirect($onSuccessRedirect)->with('success', $model->getHumanName(false).' updated successfully');
    }

    /**
     * Handles the submission of a deleting a manageable model
     * @param  mixed $request
     * @param  mixed $table
     * @param  mixed $id
     * @return View | RedirectResponse
     */
    public function manageableModelDeleteSubmit(Request $request, string $table, int $id): View | RedirectResponse
    {
        // Get this model's class
        $modelClass = $this->getManageableModelFromTable($table);

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
     * Manage users account
     * @param  mixed $request
     * @return View | RedirectResponse
     */
    public function manageAccount(Request $request): View | RedirectResponse
    {
        // Get user
        $user = Auth::user();

        // Check if user exists
        if ($user == null) {
            return redirect()->route('admin.dashboard')->with('error', 'User does not exist');
        }

        // Get manageable fields
        $fields = $user->getManageableFields(ModelPageType::Edit);

        // Pass manageable fields to view
        return view('admin.manageable-models.edit', [
            'model' => $user,
            'fields' => $fields,
            'pageTitle' => 'Manage account',
            'submitRoute' => $user->getSubmitRoute(ModelPageType::Edit),
            'submitText' => 'Update account',
            'onSuccessRedirect' => route('admin.account.manage'),
        ]);
    }

    /**
     * Logs in as the user with the given id
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
     * Returns a string of the fully qualified model class name from the table name
     * @param  mixed $table
     * @return string
     */
    private function getManageableModelFromTable(string $table): string
    {
        $modelString = 'App\Models\\'.Str::studly(Str::singular($table));

        // Fail if class does not exist
        if (class_exists($modelString) == false) {
            throw new \Exception('Class '.$modelString.' does not exist');
        }

        // Fail if is not a Model
        if (is_subclass_of($modelString, Model::class) == false) {
            throw new \Exception($modelString.' does not extend '.Model::class);
        }

        // Fail if model does not have the ManageableModel trait
        if (in_array(ManageableModel::class, class_uses($modelString)) == false) {
            throw new \Exception('Model '.$modelString.' does not have the ManageableModel trait');
        }

        return $modelString;
    }

    /**
     * Updates the given model's fields from the request, note that the request must be validated before this is called,
     * the model must have the ManageableModel trait, and the fields must be an array of ManageableField objects,
     * this allows us to be certain that the request has been validated, and both the model and fields are also valid.
     * @param  mixed $model
     * @param  mixed $fields
     * @param  mixed $request
     * @return Model
     */
    private function updateModelFieldsFromRequest($model, $fields, $request): Model
    {
        // Check if model has the ManageableModel trait
        if (in_array(ManageableModel::class, class_uses($model)) == false) {
            throw new \Exception('Model '.get_class($model).' must have the ManageableModel trait to update fields from request');
        }

        // Check if request has been validated, this is to ensure we intentionally validated the
        // request before attempting to update the model
        if ($request->get('__validated__') != true) {
            throw new \Exception('Request has not been validated');
        }

        // Get all columns that belong to this model's table
        $columns = \Schema::getColumnListing($model->getTable());

        foreach ($fields as $field) {
            // Fail if field is not a manageable field
            if (!($field instanceof \App\Classes\ManageableFields\ManageableField)) continue;

            // Continue to next field if trying to update a readonly field
            if ($field->getData('readonly')) continue;

            // Get field name
            $fieldName = $field->name;

            // Check if table column exists
            if(in_array($fieldName, $columns) == false) {
                dd('Field '.$fieldName.' does not exist on table '.$model->getTable());
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

        return $model;
    }
}
