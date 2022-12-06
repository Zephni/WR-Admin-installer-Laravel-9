<x-admin.layout>

    <x-slot name="title">Dashboard</x-slot>

    <h1 class="mt-8 text-4xl text-center font-light">TODO: BUILD DEFAULT ADMINISTRATION</h1>
    <hr class="my-6 border-slate-500">
    <p class="text-center text-2xl font-light">
        Here a user will be able to manage all of the backend aspect of the website.<br />
        By creating a table migration and model, and then extending with the ManageableModel class
        we can create a full CRUD interface for the model automatically. The user can then manage
        these tables fully from the admin dashboard.<br />
        <br />
        A table model (resource) must be in the user permissions table to be able to affirm that they
        can create, read, update, or delete the resource. This is to prevent users from accessing resources
        that they should not have access to.<br />
    </p>

</x-admin.layout>
