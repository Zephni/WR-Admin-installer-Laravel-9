# WR Admin (Laravel 9)

Note: The laravel application (including artisan commands) is located in the `laravel-application` directory.

## Installation

1. Clone the repository
2. Change directory to the `laravel-application` directory with `cd laravel-application`
3. Run `composer install`
4. Run `npm install`
5. Duplicate `.env.example` and rename it to `.env`, then fill in the appropriate environment variables
6. Run `php artisan migrate:fresh` to run the migrations, be careful that this is not over an existing database

## Running / Development
1. Run `npm run dev` to compile the assets (note with Laravel 9+ this is a continuous process and must be stopped with Ctrl+C)
2. Run `php artisan serve` to start the development server (this is also a continuous process and must be stopped with Ctrl+C)

## Creating a user
1. After running `php artisan serve`, you can create a user by visiting `http://localhost:8000/register`

## Database tables / Models
1. The database tables are located in the `laravel-application/database/migrations` directory
2. The models are located in the `laravel-application/app/Models` directory

## Admin navigation
1. The admin navigation can be modified in the `laravel-application/config/admin-navigation.php` file

## ManageableModels trait (`app/traits/ManageableModel.php`)
1. To make a model manageable, add the `ManageableModel` trait to the model, and override the appropriate methods (Check the `ManageableModel` trait for more information)
2. A ManageableModel is a model that can be managed within the admin panel (Given the user has the correct permissions), they are added automatically to the admin panel navigation

### ManageableModel methods
- `isViewable` - Returns whether the model is viewable in the admin panel
- `isCreatable` - Returns whether the model is creatable in the admin panel
- `isEditable` - Returns whether the model is editable in the admin panel
- `isDeletable` - Returns whether the model is deletable in the admin panel
- `validate` - Returns the validation rules for the model
- `browseActions` - Returns the browse actions for the model (eg. Edit, Delete, etc.)
- `onCreateHook` - Called when a model is created
- `onEditHook` - Called when a model is edited
- `onDeleteHook` - Called when a model is deleted
- `getBrowsableColumns` - Returns the columns that are displayed in the browse view
- `getManageableFields` - Returns the fields (ManageableField's) that are displayed in the create and edit views

## ManageableFields extendable class (`app/Classes/ManageableFields/ManageableField.php`)
1. `ManageableField`s are used to define the fields that are displayed in the admin panel for a given model
2. To create a new `ManageableField`, add it to the `ManageableFields` directory, extend the `ManageableField` class and override the appropriate methods (Check the `ManageableField` class for more information)

### ManageableField methods
- `__construct` - The constructor for the field, this is where you should set the field's base properties
- `render` - Returns the HTML for the field (by means of a view)
- `getValue` - Returns the value for the field (This includes returning the `old` value if the request fails validation)
- `getLabel` - Returns the label for the field (This is a pretty name, displayed by default above the field)
- `getPlaceholder` - Returns the placeholder for the field (This is a hint for the user, displayed by default inside the field)