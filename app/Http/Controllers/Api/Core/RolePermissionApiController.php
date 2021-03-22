<?php
namespace App\Http\Controllers\Api\Core;
use App\Http\Controllers\Base\Core\AdministratorController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\Core\RolePermissionController;
use Illuminate\Http\Request;

class RolePermissionApiController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Role"},
     *     path="/api/role/get/{roleId}",
     *     description="Get specified role info",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         in="path",
     *         name="roleId",
     *         description="The role id",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successfully get specified role info",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                         "id": 2,
     *                         "name": "director",
     *                         "guard_name": "api",
     *                         "display_name": "Director",
     *                         "description": null,
     *                         "created_at": "2020-05-18 09:57:40",
     *                         "updated_at": "2020-05-18 09:57:40"
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(response="500", description="Unable to get role info"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="No permission to access role info")
     * )
     */
    public function getRoleApi($roleId, Request $request)
    {
        if (!$request->user()->hasPermissionTo('role:view')) {
            return response()->json(['message' => 'No Permission to Access'], 403);
        }

        $role = RolePermissionController::getRole($roleId);
        if (!$role) {
            return response()->json(['message' => 'Unable to get role profile, please try again'], 500);
        }
        return response()->json($role, 200);
    }

    /**
     * @OA\Get(
     *     tags={"Role"},
     *     path="/api/role/list",
     *     description="Get all roles",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Successfully get all roles",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                         {
     *                         "id": 1,
     *                         "name": "superuser",
     *                         "guard_name": "api",
     *                         "display_name": "Superuser",
     *                         "description": null,
     *                         "created_at": "2020-05-18 09:57:40",
     *                         "updated_at": "2020-05-18 09:57:40"
     *                         },
     *                         {
     *                         "id": 2,
     *                         "name": "director",
     *                         "guard_name": "api",
     *                         "display_name": "Director",
     *                         "description": null,
     *                         "created_at": "2020-05-18 09:57:40",
     *                         "updated_at": "2020-05-18 09:57:40"
     *                         }
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(response="500", description="Unable to get role list"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="No permission to access role list")
     * )
     */
    public function getRolesApi(Request $request)
    {
    	if (!$request->user()->hasPermissionTo('role:list')) {
            return response()->json(['message' => 'No Permission to Access'], 403);
        }

        $roles = RolePermissionController::getRoles($request->query());
        if (empty($roles)) {
            return response()->json(['message' => 'Unable to get roles list, please try again'], 500);
        }
        return response()->json($roles, 200);
    }

    /**
     * @OA\Get(
     *     tags={"Role"},
     *     path="/api/permission/list",
     *     description="Get all permissions",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Successfully get all permissions",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                         {
     *                         "id": 1,
     *                         "name": "administrator:self:password:update",
     *                         "guard_name": "api",
     *                         "display_name": "Update Password",
     *                         "collection": "Administrator Self Management",
     *                         "description": "Update login password",
     *                         "created_at": "2020-05-18 09:57:40",
     *                         "updated_at": "2020-05-18 09:57:40"
     *                         },
     *                         {
     *                         "id": 2,
     *                         "name": "administrator:self:update",
     *                         "guard_name": "api",
     *                         "display_name": "Update Profile",
     *                         "collection": "Administrator Self Management",
     *                         "description": "Update profile",
     *                         "created_at": "2020-05-18 09:57:40",
     *                         "updated_at": "2020-05-18 09:57:40"
     *                         },
     *                         {
     *                         "id": 3,
     *                         "name": "administrator:create",
     *                         "guard_name": "api",
     *                         "display_name": "Create Administrator Account",
     *                         "collection": "Administrator Management",
     *                         "description": "Create administrator account and assign roles",
     *                         "created_at": "2020-05-18 09:57:40",
     *                         "updated_at": "2020-05-18 09:57:40"
     *                         }
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(response="500", description="Unable to get permission list"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="No permission to access permission list")
     * )
     */
    public function getPermissionsApi(Request $request)
    {
        if (!$request->user()->hasPermissionTo('permission:list')) {
            return response()->json(['message' => 'No Permission to Access'], 403);
        }

        $permissions = RolePermissionController::getPermissions($request->query());
        if (empty($permissions)) {
            return response()->json(['message' => 'Unable to get permissions list, please try again'], 500);
        }
        return response()->json($permissions, 200);
    }

    /**
     * @OA\Get(
     *     tags={"Role"},
     *     path="/api/role/permissions/list/{roleId}",
     *     description="Get permissions of the role",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         in="path",
     *         name="roleId",
     *         description="The role id",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successfully get permissions of the role",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                         "administrator:self:password:update",
     *                         "administrator:self:update",
     *                         "administrator:list",
     *                         "administrator:view",
     *                         "administrator:role:update",
     *                         "administrator:activated:update",
     *                         "administrator:delete",
     *                         "role:list",
     *                         "permission:list",
     *                         "permission:list",
     *                         "supplier:list",
     *                         "product:list",
     *                         "report:field:list",
     *                         "report:field:view",
     *                         "report:field:inactivate",
     *                         "report:field:reactivate",
     *                         "report:field:delete",
     *                         "platform:field:list",
     *                         "setting:integration:twilio:view",
     *                         "setting:integration:twilio:update",
     *                         "setting:list"
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(response="500", description="Unable to get role permissions"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="No permission to access role permissions")
     * )
     */
    public function getRolePermissionsApi($roleId, Request $request){
    	if (!$request->user()->hasPermissionTo('role:permission:list')) {
            return response()->json(['message' => 'No Permission to Access'], 403);
        }

        $rolePermissions = RolePermissionController::getRolePermissions($roleId);
        if (empty($rolePermissions)) {
            return response()->json(['message' => 'Unable to get role permissions, please try again'], 500);
        }
        return response()->json($rolePermissions, 200);
    }

    /**
     * @OA\Post(
     *     tags={"Role"},
     *     path="/api/role/create",
     *     description="create role",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         in="query",
     *         name="name",
     *         description="The unique role name",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="description",
     *         description="Brief role description",
     *         required=false,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Response(response="200", description="Successfully create role"),
     *     @OA\Response(response="500", description="Unable to create role"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="422", description="Given data empty or invalid"),
     *     @OA\Response(response="403", description="No permission to create role")
     * )
     */
    public function createRoleApi(Request $request){
        if (!$request->user()->hasPermissionTo('role:create')) {
            return response()->json(['message' => 'No Permission to Access'], 403);
        }

        $createRoleRequest = RolePermissionController::createRole($request);
        if (!$createRoleRequest) {
            return response()->json(['message' => 'Unable to update role permissions, please try again'], 500);
        }
        return response()->json(['message' => 'Role has been created successfully'], 200);
    }

    /**
     * @OA\Put(
     *     tags={"Role"},
     *     path="/api/role/update/{roleId}",
     *     description="Update specified role info",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         in="path",
     *         name="roleId",
     *         description="The role id",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="name",
     *         description="The unique role name",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="description",
     *         description="Brief role description",
     *         required=false,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Response(response="200", description="Successfully update specified role info"),
     *     @OA\Response(response="500", description="Unable to update specified role"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="422", description="Given data empty or invalid"),
     *     @OA\Response(response="403", description="No permission to update specified role")
     * )
     */
    public function updateRoleApi($roleId, Request $request){
        if (!$request->user()->hasPermissionTo('role:update')) {
            return response()->json(['message' => 'No Permission to Access'], 403);
        }

        $updateRolePermissionsRequest = RolePermissionController::updateRole($roleId, $request);
        if (!$updateRolePermissionsRequest) {
            return response()->json(['message' => 'Unable to update role permissions, please try again'], 500);
        }
        return response()->json(['message' => 'Role permissions has been updated successfully'], 200);
    }

    /**
     * @OA\Put(
     *     tags={"Role"},
     *     path="/api/role/permissions/update/{roleId}",
     *     description="Update specified role permissions",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         in="path",
     *         name="roleId",
     *         description="The role id",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="permissions",
     *         description="The updated role permissions",
     *         required=false,
     *         allowEmptyValue=true
     *     ),
     *     @OA\Response(response="200", description="Successfully update role permissions"),
     *     @OA\Response(response="500", description="Unable to update role permissions"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="422", description="Given data empty or invalid"),
     *     @OA\Response(response="403", description="No permission to update role permissions")
     * )
     */
    public function updateRolePermissionsApi($roleId, Request $request){
        if (!$request->user()->hasPermissionTo('role:permission:update')) {
            return response()->json(['message' => 'No Permission to Access'], 403);
        }

        $updateRolePermissionsRequest = RolePermissionController::updateRolePermissions($roleId, $request);
        if (!$updateRolePermissionsRequest) {
            return response()->json(['message' => 'Unable to update role permissions, please try again'], 500);
        }
        return response()->json(['message' => 'Role permissions has been updated successfully'], 200);
    }

    /**
     * @OA\Delete(
     *     tags={"Role"},
     *     path="/api/role/delete/{roleId}",
     *     description="delete role",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         in="path",
     *         name="roleId",
     *         description="The role id",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Response(response="200", description="Successfully delete role"),
     *     @OA\Response(response="500", description="Unable to delete administrator"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="No permission to delete role"),
     *     @OA\Response(response="409", description="Superuser role can not be deleted or role is in use")
     * )
     */
    public function deleteRoleApi($roleId, Request $request){
        if (!$request->user()->hasPermissionTo('role:delete')) {
            return response()->json(['message' => 'No Permission to Access'], 403);
        }

        $deleteRoleRequest = RolePermissionController::deleteRole($roleId, $request);
        if (!$deleteRoleRequest) {
            return response()->json(['message' => 'Unable to delete role, please try again'], 500);
        }
        return response()->json(['message' => 'Role has been deleted successfully'], 200);
    }

    /**
     * @OA\Get(
     *     tags={"Role"},
     *     path="/api/role/administrator/list/{roleId}",
     *     description="Get administrators in specified role",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         in="path",
     *         name="roleId",
     *         description="The role id",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successfully get administrators of the role",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                         {
     *                         "id": 1,
     *                         "username": "zetadigital",
     *                         "given_name": "Zeta",
     *                         "family_name": "Digital",
     *                         "mobile": "416658888",
     *                         "email": "",
     *                         "activated": 1,
     *                         "created_at": "2020-05-18 09:57:40",
     *                         "updated_at": "2020-05-20 13:04:15",
     *                         "roles": {
     *                         {
     *                         "id": 1,
     *                         "name": "superuser",
     *                         "guard_name": "api",
     *                         "display_name": "Superuser",
     *                         "description": null,
     *                         "created_at": "2020-05-18 09:57:40",
     *                         "updated_at": "2020-05-18 09:57:40",
     *                         "pivot": {
     *                         "model_id": 1,
     *                         "role_id": 1,
     *                         "model_type": "App\\Http\\Models\\Administrator"
     *                         }
     *                         }
     *                         }
     *                         }
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(response="500", description="Unable to get administratos in specified role"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="No permission to access administrators in specified role")
     * )
     */
    public function getRoleUsersApi($roleId, Request $request){
        if (!$request->user()->hasPermissionTo('role:administrator:list')) {
            return response()->json(['message' => 'No Permission to Access'], 403);
        }

        $roleUsersRequest = AdministratorController::getAdministratorsViaRoleId($roleId);
        if (!$roleUsersRequest) {
            return response()->json(['message' => 'Unable to get role users, please try again'], 500);
        }
        return response()->json($roleUsersRequest, 200);
    }
}

