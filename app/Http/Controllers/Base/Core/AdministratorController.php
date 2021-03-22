<?php
namespace App\Http\Controllers\Base\Core;
use App\Http\Controllers\Controller;
use App\Http\Models\Administrator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use App\Exceptions\ZetaException;

class AdministratorController extends Controller
{
    public static function administratorLogin($request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $credentialsWithUsername =['username' => $request->get('username'), 'password' => $request->get('password'), 'activated' => 1];

        if(!Auth::attempt($credentialsWithUsername))
            return [];

        Passport::personalAccessTokensExpireIn(now()->addHours(24));
        $administrator = $request->user();
        $tokenResult = $administrator->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        ActivityController::createActivity($administrator, null, "Login");

        return [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'permissions' => RolePermissionController::getRolePermissions($administrator->roles[0]->id),
            'expires_at' => Carbon::parse(
                $token->expires_at
            )->toDateTimeString()
        ];
    }

    public static function administratorLogout($request)
    {
        $loggedInAdministrator = $request->user();
        ActivityController::createActivity($loggedInAdministrator, null, "Logout");
        return $request->user()->token()->revoke();
    }

    public static function getLoggedInAdministrator($request)
    {
        $loggedInUser = (array)$request->user();
        $loggedInUser = (object)$loggedInUser["\0*\0" . 'attributes'];
        unset($loggedInUser->password);
        $administrator = self::getAdministrator($loggedInUser->id, []);
        $loggedInUser->role = ucfirst($administrator->getRoleNames()[0]);
        return $loggedInUser;
    }

    public static function getLoggedInAdministratorPermissions($request){
        $loggedInUser = (array)$request->user();
        $loggedInUser = (object)$loggedInUser["\0*\0" . 'attributes'];
        $administrator = self::getAdministrator($loggedInUser->id, []);
        if (!$administrator)
            return [];
        return $administrator->getAllPermissions();
    }

    public static function updateLoggedInAdministratorPassword($request)
    {
        $request->validate([
            'password' => 'required|string',
            'new_password' => 'required|confirmed|min:6'
        ]);

        $currentPassword = $request->get('password');
        if (!\Hash::check($currentPassword, $request->user()->password))
            return null;

        $loggedInUser = (array)$request->user();
        $loggedInUser = (object)$loggedInUser["\0*\0" . 'attributes'];
        $administrator = self::getAdministrator($loggedInUser->id, []);

        $updatePassword = $administrator->update([
            'password' => \Hash::make($request->get('new_password'))
        ]);

        if ($updatePassword)
            ActivityController::createActivity($administrator, $administrator, 'Update', $administrator->getChanges());

        return $updatePassword;
    }

    public static function updateLoggedInAdministrator($request)
    {
        $loggedInUser = (array)$request->user();
        $loggedInUser = (object)$loggedInUser["\0*\0" . 'attributes'];

        $request->validate([
            'username' => 'required|string|unique:administrators,username,' . $loggedInUser->id . ',id',
            'given_name' => 'required|string',
            'family_name' => 'required|string',
            'mobile' => 'string',
            'email' => 'string|email'
        ]);

        $administrator = self::getAdministrator($loggedInUser->id, []);

        $updateAdministrator = $administrator->update([
            'username' => strtolower($request->get('username')),
            'given_name' => ucfirst(strtolower($request->get('given_name'))),
            'family_name' => ucfirst(strtolower($request->get('family_name'))),
            'mobile' => $request->get('mobile'),
            'email' => strtolower($request->get('email'))
        ]);

        if ($updateAdministrator) {
            ActivityController::createActivity($administrator, $administrator, 'Update', $administrator->getChanges());
        }

        return $updateAdministrator;
    }

    public static function createAdministrator($request)
    {
        $request->validate([
            'username' => 'required|string|unique:administrators,username,NULL,id',
            'password' => 'required|string',
            'given_name' => 'required|string',
            'family_name' => 'required|string',
            'mobile' => 'string',
            'email' => 'string|email',
            'role' => 'required|numeric'
        ]);

        $roleId = $request->get('role');
        if (in_array($roleId, RolePermissionController::getSuperuserRoleIds())) {
            throw new ZetaException(409, 'User can not be created as superuser');
        }

        $newAdmin = new Administrator([
            'mobile' => $request->get('mobile'),
            'username' => $request->get('username'),
            'password' => \Hash::make($request->get('password')),
            'given_name' => ucfirst(strtolower($request->get('given_name'))),
            'family_name' => ucfirst(strtolower($request->get('family_name'))),
            'email' => strtolower($request->get('email'))
        ]);
        $newAdmin->save();
        $newAdmin->assignRole($roleId);

        if ($newAdmin){
            $loggedInAdministrator = $request->user();
            ActivityController::createActivity($loggedInAdministrator, $newAdmin, "Create");
        }

        return $newAdmin;
    }

    public static function getAdministrators($withParams, $withCountParams = null, $query = [])
    {
        return Administrator::where($query)->with($withParams)->withCount($withCountParams)->get();
    }

    public static function getAdministrator($administratorId, $withParams, $withCountParams = null, $computedParams = [])
    {
        return Administrator::with($withParams)->withCount($withCountParams)->findOrFail($administratorId)->append($computedParams);
    }

    public static function getAdministratorsViaRoleId($roleId)
    {
        return Administrator::role($roleId)->with('roles')->get();
    }

    public static function updateAdministrator($administratorId, $request)
    {
        $request->validate([
            'username' => 'required|string|unique:administrators,username,' . $administratorId . ',id,activated,1',
            'given_name' => 'required|string',
            'family_name' => 'required|string',
            'mobile' => 'string',
            'email' => 'string|email'
        ]);
        $administrator = self::getAdministrator($administratorId, []);

        if ($administrator->hasRole(RolePermissionController::getSuperuserRoleIds())) {
            throw new ZetaException(409, 'Super user can not be updated');
        }

        $updateAdministrator = $administrator->update([
            'mobile' => $request->get('mobile'),
            'username' => strtolower($request->get('username')),
            'given_name' => ucfirst(strtolower($request->get('given_name'))),
            'family_name' => ucfirst(strtolower($request->get('family_name'))),
            'email' => strtolower($request->get('email'))
        ]);

        if ($updateAdministrator){
            $loggedInAdministrator = $request->user();
            ActivityController::createActivity($loggedInAdministrator, $administrator, 'Update', $administrator->getChanges());
        }

        return $updateAdministrator;
    }

    public static function updateAdministratorRole($administratorId, $request)
    {
        $request->validate([
            'role' => 'required|numeric'
        ]);

        $superuserRoleIds = RolePermissionController::getSuperuserRoleIds();
        $administrator = self::getAdministrator($administratorId, []);
        if ($administrator->hasRole($superuserRoleIds)) {
            throw new ZetaException(409, 'Super user can not be updated');
        }

        $roleId = $request->get('role');
        if (in_array($roleId, $superuserRoleIds)) {
            throw new ZetaException(409, 'User role can not be updated as superuser');
        }
        $updateRoles = $administrator->syncRoles($roleId);

        $updateRoles = $updateRoles->roles->toArray()[0] != $roleId;

        if ($updateRoles){
            $loggedInAdministrator = $request->user();
            ActivityController::createActivity($loggedInAdministrator, $administrator, 'Update', ['role' => RolePermissionController::getRole($request->get('role'))->name]);
        }
        return $updateRoles;
    }

    public static function updateAdministratorActivatedStatus($administratorId, $request)
    {
        $administrator = self::getAdministrator($administratorId, []);

        $inactivateAdministrator = $administrator->update([
            'activated' => !$administrator->activated
        ]);

        if ($inactivateAdministrator) {
            $loggedInAdministrator = $request->user();
            ActivityController::createActivity($loggedInAdministrator, $administrator, $administrator->activated ? 'Reactivate' : 'Inactivate');
        }

        return $inactivateAdministrator;
    }

    public static function deleteAdministrator($administratorId, $request)
    {
        $administrator = self::getAdministrator($administratorId, []);

        if ($administrator->hasRole(RolePermissionController::getSuperuserRoleIds())) {
            throw new ZetaException(409, 'Super user can not be deleted');
        }

        if ($administrator->activated == 1) {
            throw new ZetaException(409, 'Activated user can not be deleted, please inactivate user first');
        }

        $deleteAdministrator = $administrator->delete();

        if ($deleteAdministrator) {
            $loggedInAdministrator = $request->user();
            ActivityController::createActivity($loggedInAdministrator, $administrator, 'Delete');
        }

        return $deleteAdministrator;
    }
}
