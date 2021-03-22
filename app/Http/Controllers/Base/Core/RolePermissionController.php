<?php
namespace App\Http\Controllers\Base\Core;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Exceptions\ZetaException;

class RolePermissionController extends Controller
{
    public static function getSuperuserRoleIds()
    {
        return [1, 2];
    }

    public static function getRoles($query = [])
    {
        return Role::where($query)->get();
    }

    public static function getRole($roleId)
    {
        return Role::findOrFail($roleId);
    }

    public static function getPermissions($query = [])
    {
        return Permission::where($query)->get();
    }

    public static function getRolePermissions($roleId)
    {
        $role = self::getRole($roleId);
        return $role->getPermissionNames();
    }

    public static function createRole($request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,NULL,id',
            'description' => 'string'
        ]);

        $newRole = new Role([
            'name' => strtolower($request->get('name')),
            'guard_name' => 'api',
            'display_name' => ucfirst(strtolower($request->get('name'))),
            'description' => $request->get('description')
        ]);
        $newRole->save();

        if ($newRole){
            $loggedInAdministrator = $request->user();
            ActivityController::createActivity($loggedInAdministrator, $newRole, 'Create');
        }

        return $newRole;
    }

    public static function updateRole($roleId, $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $roleId . ',id',
            'description' => 'string'
        ]);

        $role = self::getRole($roleId);
        if (in_array($role->id, self::getSuperuserRoleIds())) {
            throw new ZetaException(409, 'Superuser role can not be updated');
        }

        $updateRole = $role->update([
            'name' => strtolower($request->get('name')),
            'display_name' => ucfirst(strtolower($request->get('name'))),
            'description' => $request->get('description')
        ]);

        if ($updateRole){
            $loggedInAdministrator = $request->user();
            ActivityController::createActivity($loggedInAdministrator, $role, 'Update', $role->getChanges());
        }

        return $updateRole;
    }

    public static function updateRolePermissions($roleId, $request)
    {
        if (in_array($roleId, self::getSuperuserRoleIds())) {
            throw new ZetaException(409, 'Superuser role can not be updated');
        }
        $role = self::getRole($roleId);
        $permissions = explode(',', preg_replace('/\s/', '', $request->get('permissions')));

        $updateRolePermissions = $role->syncPermissions($permissions);

        if ($updateRolePermissions){
            $loggedInAdministrator = $request->user();
            ActivityController::createActivity($loggedInAdministrator, $role, 'Update', ['permissions' => $request->get('permissions')]);
        }

        return $updateRolePermissions;
    }

    public static function deleteRole($roleId, $request)
    {
        $role = self::getRole($roleId);
        if (in_array($role->id, self::getSuperuserRoleIds())) {
            throw new ZetaException(409, 'Super user role can not be deleted');
        }

        $administrators = AdministratorController::getAdministratorsViaRoleId($roleId);
        if (!$administrators->isEmpty()) {
            throw new ZetaException(409, 'Role can not be delete due to be used for administrator');
        }

        $deleteRole = $role->delete();

        if ($deleteRole){
            $loggedInAdministrator = $request->user();
            ActivityController::createActivity($loggedInAdministrator, $role, 'Delete');
        }

        return $role;
    }
}

