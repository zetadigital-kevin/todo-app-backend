<?php
namespace App\Http\Controllers\Api\Core;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\Core\AdministratorController;
use Illuminate\Http\Request;

class AdministratorApiController extends Controller
{
    /**
     *
     * @OA\Post(
     *     tags={"Administrator"},
     *     path="/api/administrator/login",
     *     description="Administrator log in",
     *     @OA\Parameter(
     *         in="query",
     *         name="username",
     *         description="The unique username of administrator",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="password",
     *         description="The password needed for administrator to log in",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successfully log in",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                         "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjMxYWQ5MzgzOWU4NzEzMjhjN2FkNjFmYzc2NjU0ZGZiZGFkNmZkY2U4MDY1MzNiMjAyMWQ1MzIzM2I3ZGVkMTJmMTYzMWE5Y2Q2N2NlNTY3In0.eyJhdWQiOiIxIiwianRpIjoiMzFhZDkzODM5ZTg3MTMyOGM3YWQ2MWZjNzY2NTRkZmJkYWQ2ZmRjZTgwNjUzM2IyMDIxZDUzMjMzYjdkZWQxMmYxNjMxYTljZDY3Y2U1NjciLCJpYXQiOjE1ODk4NDg4ODIsIm5iZiI6MTU4OTg0ODg4MiwiZXhwIjoxNTg5OTM1MjgyLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.bZtKfJbpsg4kky2QU0GuEXHMd4IWPEnloTXRUfyUQK6ba1Vy8go1AXaY9sMKmU74T_ZKBHFtNfVjViQDsmUQZk9GwZnFvywfBDK57RQ9a22R7YsSqEAhAIj8T90vxL5Eu5Ku_YCS5aBwNKgcc0RwnQ7ym7H8rq5r-SIMKvEF15Lg4pTTuh095x7AHxW6Sui-CoZ3xWqhkLpMCvi1AulYtAOlCMXF62vZ86EH_rj_eF29IZxotZ0U86aIEIjJeOAQmDImna4pWi4F_GcLEcwpGsJorGGk_Buj6BiIsGj8rG9878t3s2BRzeto4z8Hjy45TPs3aIU9qYbm7z6UYhspIj4BpulATygzuAEMP5OA3yAO3qnDlSErKFZP-Dg8k06uSswoAgncRD_Ku9rBZTHBJKXCQslphyO13jGjgrg3yeIyWPgJ-Re2fr7ctv8yQvJSQCJqc1I8iS2v8cEb2wD5YEPKHRm8dJU7s4nbvNi15m44ke5f6bAgtzOeG0aeAtfYbd4Uy8FbmEnKpdDX2klQDXnIvrvE1wbBlkXdxEP-TrJb_hvj38NqgywKJnZAvKuO7NHjIOYsyHZIeCtG7BTrhobv6K2sSnX8AfopIfGUF4z7uRJ3M8RBOW6XVGeSNHE0LsrtwM5d4vGUfd7yzQrnxgsPJ7GxZ0IIMXsSsas-yec",
     *                         "token_type": "Bearer",
     *                         "permissions": {
     *                             "administrator:self:password:update",
     *                             "administrator:self:update",
     *                             "administrator:create",
     *                             "administrator:list",
     *                             "administrator:view",
     *                         },
     *                         "expires_at": "2020-05-20 10:41:22"
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="422", description="Given data empty or invalid")
     * )
     */
    public function administratorLoginApi(Request $request)
    {
        $loginResult = AdministratorController::administratorLogin($request);
        if (empty($loginResult)) {
            return response()->json(['message' => 'Invalid username or password'], 401);
        }
        return response()->json($loginResult, 200);
    }

    /**
     * @OA\Get(
     *     tags={"Administrator"},
     *     path="/api/administrator/logout",
     *     description="Administrator log out",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Response(response="200", description="Successfully log out"),
     *     @OA\Response(response="500", description="Unable to revoke token and logout")
     * )
     */
    public function administratorLogoutApi(Request $request)
    {
        $logoutResult = AdministratorController::administratorLogout($request);
        if (!$logoutResult) {
            return response()->json(['message' => 'Unable to revoke token and logout, please try again'], 500);
        }
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    /**
     * @OA\Get(
     *     tags={"Administrator"},
     *     path="/api/administrator/get",
     *     description="Get logged in administrator info",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Successfully get logged in administrator info",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="id",
     *                         type="string",
     *                         description="Unique administrator id"
     *                     ),
     *                     @OA\Property(
     *                         property="username",
     *                         type="string",
     *                         description="Unique administrator username"
     *                     ),
     *                     @OA\Property(
     *                         property="given_name",
     *                         type="string",
     *                         description="Administrator given name"
     *                     ),
     *                     @OA\Property(
     *                         property="family_name",
     *                         type="string",
     *                         description="Administrator family name"
     *                     ),
     *                     @OA\Property(
     *                         property="mobile",
     *                         type="string",
     *                         description="Administrator mobile number"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         description="Administrator email address"
     *                     ),
     *                     @OA\Property(
     *                         property="activated",
     *                         type="string",
     *                         description="Administrator activation status"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         description="Creation time of administrator profile"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         description="Last update time of administrator profile"
     *                     ),
     *                     @OA\Property(
     *                         property="role",
     *                         type="string",
     *                         description="Administrator role name"
     *                     ),
     *                     example={
     *                         "id": 1,
     *                         "username": "zetadigital",
     *                         "given_name": "Zeta",
     *                         "family_name": "Digital",
     *                         "mobile": "416651111",
     *                         "email": "support@zetadigital.com.au",
     *                         "activated": 1,
     *                         "created_at": "2020-05-18 09:57:40",
     *                         "updated_at": "2020-05-18 11:45:21",
     *                         "role": "Superuser"
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(response="500", description="Unable to get logged in administrator info"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     */
    public function getLoggedInAdministratorApi(Request $request)
    {
        $loggedInAdmin = AdministratorController::getLoggedInAdministrator($request);
        if (!$loggedInAdmin) {
            return response()->json(['message' => 'Unable to get logged in administrator information, please try again'], 500);
        }
        return response()->json($loggedInAdmin, 200);
    }

    /**
     * @OA\Get(
     *     tags={"Administrator"},
     *     path="/api/administrator/permissions/list",
     *     description="Get logged in administrator permissions",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Successfully get logged in administrator permissions",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             description="Permission id",
     *                             example=1,
     *                         ),
     *                         @OA\Property(
     *                             property="name",
     *                             type="string",
     *                             description="Permission name",
     *                             example="administrator:self:password:update"
     *                         ),
     *                         @OA\Property(
     *                             property="guard_name",
     *                             type="string",
     *                             description="Guard name",
     *                             example="api"
     *                         ),
     *                         @OA\Property(
     *                             property="display_name",
     *                             type="string",
     *                             description="Displayed permission name",
     *                             example="Update Password"
     *                         ),
     *                         @OA\Property(
     *                             property="collection",
     *                             type="string",
     *                             description="Permission collection name",
     *                             example="Administrator Self Management"
     *                         ),
     *                         @OA\Property(
     *                             property="description",
     *                             type="string",
     *                             description="Brief description of permission",
     *                             example="Update login password"
     *                         ),
     *                         @OA\Property(
     *                             property="created_at",
     *                             type="string",
     *                             description="permission creation time",
     *                             example="2020-05-18 09:57:40"
     *                         ),
     *                         @OA\Property(
     *                             property="updated_at",
     *                             type="string",
     *                             description="most recent permission update time",
     *                             example="2020-05-18 09:57:40"
     *                         ),
     *                         @OA\Property(
     *                             property="pivot",
     *                             description="pivot info",
     *                             @OA\Property(
     *                                 property="role_id",
     *                                 type="integer",
     *                                 description="role id",
     *                                 example=1,
     *                             ),
     *                             @OA\Property(
     *                                 property="permission_id",
     *                                 type="integer",
     *                                 description="permission id",
     *                                 example=1
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(response="500", description="Unable to get logged in administrator permissions"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     */
    public function getLoggedInAdministratorPermissionsApi(Request $request)
    {
        $loggedInAdminPermissions = AdministratorController::getLoggedInAdministratorPermissions($request);
        if (!$loggedInAdminPermissions) {
            return response()->json(['message' => 'Unable to get logged in administrator permissions, please try again'], 500);
        }
        return response()->json($loggedInAdminPermissions, 200);
    }

    /**
     * @OA\Put(
     *     tags={"Administrator"},
     *     path="/api/administrator/password/update",
     *     description="Update logged in administrator password",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         in="query",
     *         name="password",
     *         description="The current administrator password",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="new_password",
     *         description="The new password for update",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="new_password_confirmation",
     *         description="Confirm new password",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Response(response="200", description="Successfully update logged in administrator password"),
     *     @OA\Response(response="500", description="Unable to update logged in administrator password"),
     *     @OA\Response(response="401", description="Unauthorized or current password is invalid"),
     *     @OA\Response(response="422", description="Given data empty or invalid"),
     *     @OA\Response(response="403", description="No permission to update password")
     * )
     */
    public function updateLoggedInAdministratorPasswordApi(Request $request)
    {
        if (!$request->user()->hasPermissionTo('administrator:self:password:update')){
            return response()->json(['message' => 'No Permission to Access'], 403);
        }

        $updateLoggedInAdminPasswordRequest = AdministratorController::updateLoggedInAdministratorPassword($request);
        if (is_null($updateLoggedInAdminPasswordRequest)) {
            return response()->json(['message' => 'Current password is invalid, please try again'], 401);
        }
        if (!$updateLoggedInAdminPasswordRequest) {
            return response()->json(['message' => 'Unable to update password, please try again'], 500);
        }
        return response()->json(['message' => 'Password has been updated successfully'], 200);
    }

    /**
     * @OA\Put(
     *     tags={"Administrator"},
     *     path="/api/administrator/update",
     *     description="Update logged in administrator info",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         in="query",
     *         name="username",
     *         description="The unique username of administrator",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="given_name",
     *         description="The given name of administrator",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="family_name",
     *         description="The family name of administrator",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="mobile",
     *         description="The unique username of administrator",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="email",
     *         description="The email of administrator",
     *         required=false,
     *     ),
     *     @OA\Response(response="200", description="Successfully update administrator info"),
     *     @OA\Response(response="500", description="Unable to update logged in administrator"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="422", description="Given data empty or invalid"),
     *     @OA\Response(response="403", description="No permission to update logged in administrator")
     * )
     */
    public function updateLoggedInAdministratorApi(Request $request)
    {
        if (!$request->user()->hasPermissionTo('administrator:self:update')){
            return response()->json(['message' => 'No Permission to Access'], 403);
        }

        $updateLoggedInAdminRequest = AdministratorController::updateLoggedInAdministrator($request);
        if (!$updateLoggedInAdminRequest) {
            return response()->json(['message' => 'Unable to update profile information, please try again'], 500);
        }
        return response()->json(['message' => 'Profile information has been updated successfully'], 200);
    }

    /**
     * @OA\Post(
     *     tags={"Administrator"},
     *     path="/api/administrator/create",
     *     description="create administrator",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         in="query",
     *         name="username",
     *         description="The unique username of administrator",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="password",
     *         description="The administrator password",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="given_name",
     *         description="The given name of administrator",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="family_name",
     *         description="The family name of administrator",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="mobile",
     *         description="The unique username of administrator",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="email",
     *         description="The email of administrator",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="role",
     *         description="The role id of administrator",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successfully create administrator",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                         "mobile": null,
     *                         "username": "Elifeng",
     *                         "given_name": "Elijah",
     *                         "family_name": "Feng",
     *                         "email": "",
     *                         "updated_at": "2020-05-20 10:36:32",
     *                         "created_at": "2020-05-20 10:36:32",
     *                         "id": 5,
     *                         "roles": {
     *                           {
     *                             "id": 4,
     *                             "name": "accountant",
     *                             "guard_name": "api",
     *                             "display_name": "Accountant",
     *                             "description": null,
     *                             "created_at": "2020-05-20 10:32:50",
     *                             "updated_at": "2020-05-20 10:32:50",
     *                             "pivot": {
     *                             "model_id": 5,
     *                             "role_id": 4,
     *                             "model_type": "App\Http\Models\Administrator"
     *                             }
     *                           }
     *                         }
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(response="500", description="Unable to create administrator"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="422", description="Given data empty or invalid"),
     *     @OA\Response(response="403", description="No permission to create administrator")
     * )
     */
    public function createAdministratorApi(Request $request)
    {
        if (!$request->user()->hasPermissionTo('administrator:create')){
            return response()->json(['message' => 'No Permission to Access'], 403);
        }

        $newAdministrator = AdministratorController::createAdministrator($request);
        if (!$newAdministrator) {
            return response()->json(['message' => 'Unable to create new administrator, please try again'], 500);
        }
        return response()->json($newAdministrator, 200);
    }

    /**
     * @OA\Get(
     *     tags={"Administrator"},
     *     path="/api/administrator/list",
     *     description="Get all administrators",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="Successfully get all administrator info",
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
     *                         "mobile": "416651111",
     *                         "email": "support@zetadigital.com.au",
     *                         "activated": 1,
     *                         "created_at": "2020-05-18 09:57:40",
     *                         "updated_at": "2020-05-20 10:19:12",
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
     *                         "model_type": "App\Http\Models\Administrator"
     *                         }
     *                         }
     *                         }
     *                         }
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(response="500", description="Unable to get administrator list"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="No permission to access administrator list")
     * )
     */
    public function getAdministratorsApi(Request $request)
    {
        if (!$request->user()->hasPermissionTo('administrator:list')){
            return response()->json(['message' => 'No Permission to Access'], 403);
        }

        $administrators = AdministratorController::getAdministrators(['roles'], [], $request->query());
        if (empty($administrators)) {
            return response()->json(['message' => 'Unable to get administrators list, please try again'], 500);
        }
        return response()->json($administrators, 200);
    }

    /**
     * @OA\Get(
     *     tags={"Administrator"},
     *     path="/api/administrator/get/{administratorId}",
     *     description="Get specified administrator info",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         in="path",
     *         name="administratorId",
     *         description="The administrator id",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successfully get specified administrator info",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                         "id": 5,
     *                         "username": "Elifeng",
     *                         "given_name": "Elijah",
     *                         "family_name": "Feng",
     *                         "mobile": null,
     *                         "email": "",
     *                         "activated": 1,
     *                         "created_at": "2020-05-20 10:36:32",
     *                         "updated_at": "2020-05-20 10:36:32",
     *                         "activities": {
     *                         {
     *                         "id": 1511,
     *                         "model_type_1": "App\\Http\\Models\\Administrator",
     *                         "model_id_1": 1,
     *                         "model_type_2": "App\\Http\\Models\\Administrator",
     *                         "model_id_2": 5,
     *                         "action": "Create",
     *                         "description": "Zeta Digital created Elijah Feng",
     *                         "created_at": "2020-05-20 10:36:32",
     *                         "updated_at": "2020-05-20 10:36:32"
     *                         }
     *                         },
     *                         "roles": {
     *                         {
     *                         "id": 4,
     *                         "name": "accountant",
     *                         "guard_name": "api",
     *                         "display_name": "Accountant",
     *                         "description": null,
     *                         "created_at": "2020-05-20 10:32:50",
     *                         "updated_at": "2020-05-20 10:32:50",
     *                         "pivot": {
     *                         "model_id": 5,
     *                         "role_id": 4,
     *                         "model_type": "App\\Http\\Models\\Administrator"
     *                         }
     *                         }
     *                         }
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(response="500", description="Unable to get administrator profile"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="No permission to access administrator profile")
     * )
     */
    public function getAdministratorApi($administratorId, Request $request)
    {
        if (!$request->user()->hasPermissionTo('administrator:view')){
            return response()->json(['message' => 'No Permission to Access'], 403);
        }

        $administrator = AdministratorController::getAdministrator($administratorId, ['roles'], null, ['activities']);
        if (!$administrator) {
            return response()->json(['message' => 'Unable to get administrator profile, please try again'], 500);
        }
        return response()->json($administrator, 200);
    }

    /**
     * @OA\Put(
     *     tags={"Administrator"},
     *     path="/api/administrator/update/{administratorId}",
     *     description="Update specified administrator info",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         in="path",
     *         name="administratorId",
     *         description="The administrator id",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="username",
     *         description="The unique username of administrator",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="given_name",
     *         description="The given name of administrator",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="family_name",
     *         description="The family name of administrator",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="mobile",
     *         description="The unique username of administrator",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="email",
     *         description="The email of administrator",
     *         required=false,
     *     ),
     *     @OA\Response(response="200", description="Successfully update specified administrator info"),
     *     @OA\Response(response="500", description="Unable to update specified administrator"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="422", description="Given data empty or invalid"),
     *     @OA\Response(response="403", description="No permission to update specified administrator")
     * )
     */
    public function updateAdministratorApi($administratorId, Request $request)
    {
        if (!$request->user()->hasPermissionTo('administrator:update')){
            return response()->json(['message' => 'No Permission to Access'], 403);
        }

        $updateAdministratorRequest = AdministratorController::updateAdministrator($administratorId, $request);

        if (!$updateAdministratorRequest) {
            return response()->json(['message' => 'Unable to update administrator profile information, please try again'], 500);
        }
        return response()->json(['message' => 'Administrator information has been updated successfully'], 200);
    }

    /**
     * @OA\Put(
     *     tags={"Administrator"},
     *     path="/api/administrator/activated/update/{administratorId}",
     *     description="Update specified administrator activated status",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         in="path",
     *         name="administratorId",
     *         description="The administrator id",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="activated",
     *         description="The activation status of administrator",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Response(response="200", description="Successfully update specified administrator activated status"),
     *     @OA\Response(response="500", description="Unable to update specified administrator"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="422", description="Given data empty or invalid"),
     *     @OA\Response(response="403", description="No permission to update specified administrator")
     * )
     */
    public function updateAdministratorActivatedStatusApi($administratorId, Request $request)
    {
        if (!$request->user()->hasPermissionTo('administrator:activated:update')){
            return response()->json(['message' => 'No Permission to Access'], 403);
        }

        $updateAdministratorRequest = AdministratorController::updateAdministratorActivatedStatus($administratorId, $request);
        if (!$updateAdministratorRequest) {
            return response()->json(['message' => 'Unable to update administrator activation status, please try again'], 500);
        }
        return response()->json(['message' => 'Administrator activation status has been updated successfully'], 200);
    }

    /**
     * @OA\Delete(
     *     tags={"Administrator"},
     *     path="/api/administrator/delete/{administratorId}",
     *     description="delete administrator",
     *     security={
     *       {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         in="path",
     *         name="administratorId",
     *         description="The administrator id",
     *         required=true,
     *         allowEmptyValue=false
     *     ),
     *     @OA\Response(response="200", description="Successfully delete administrator"),
     *     @OA\Response(response="500", description="Unable to delete administrator"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="No permission to delete administrator"),
     *     @OA\Response(response="409", description="Need to inactivate administrator to delete")
     * )
     */
    public function deleteAdministratorApi($administratorId, Request $request)
    {
        if (!$request->user()->hasPermissionTo('administrator:delete')){
            return response()->json(['message' => 'No Permission to Access'], 403);
        }

        $deleteAdministratorRequest = AdministratorController::deleteAdministrator($administratorId, $request);
        if (!$deleteAdministratorRequest) {
            return response()->json(['message' => 'Unable to delete administrator account, please try again'], 500);
        }
        return response()->json(['message' => 'Administrator account has been deleted successfully'], 200);
    }
}
