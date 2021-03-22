<?php
namespace App\Http\Controllers\Api\Core;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\Core\SettingController;
use Illuminate\Http\Request;

class SettingApiController extends Controller
{
    public static function getSettingsApi($category, $scope, Request $request)
    {
        if (!$request->user()->hasPermissionTo('setting:' . $category . ':' . $scope . ':view')){
            return response()->json([
                'message' => 'No Permission to Access'
            ], 403);
        }

        $settings = SettingController::getSettings($category, $scope);
        if (empty($settings))
            return response()->json(['message' => 'Unable to update ' . $category . ' ' . $scope  .' settings, please try again'], 500);
        return response()->json($settings, 200);
    }

    public static function updateSettingsApi($category, $scope, Request $request)
    {
        if (!$request->user()->hasPermissionTo('setting:' . $category . ':' . $scope . ':update')){
            return response()->json([
                'message' => 'No Permission to Access'
            ], 403);
        }

        $updateRequest = SettingController::updateSettings($category, $scope, $request);
        if (!$updateRequest)
            return response()->json(['message' => 'Unable to update ' . $category . ' ' . $scope . ' settings, please try again'], 500);
        return response()->json(['message' => strtoupper($scope) . ' settings has been updated successfully'], 200);
    }
}
