<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Roles extends Component
{
    public $roles;
    public $permissions;
    public $selectedRole = null;
    public $rolePermissions = [];


    public function mount()
    {
        $this->loadData();
    }


    public function loadData()
    {
        $this->roles = Role::with('permissions')->get();
        $this->permissions = Permission::all();
    }

    public function editPermissions($roleId)
    {
        $this->selectedRole = Role::find($roleId);
        $this->rolePermissions = $this->selectedRole->permissions->pluck('name')->toArray();
    }

    public function updatePermissions()
    {
        $this->selectedRole->syncPermissions($this->rolePermissions);
        session()->flash('message', 'Permisos actualizados correctamente.');
        $this->loadData();
    }

    public $pageTitle = 'Gestión de Roles';
    public $componentName = 'Roles';

    public function render()
    {
        return view('livewire.admin.roles')
            ->layout('layouts.theme.app');
    }
}
