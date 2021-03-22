<?php
namespace App\Http\Controllers\Base\Core;
use App\Http\Controllers\Controller;
use App\Http\Models\Setting;
use App\Exceptions\ZetaException;


class SettingController extends Controller
{
    public static function updateSettings($category, $scope, $request)
    {
        foreach ($request->all() as $field => $value) {
            $fieldExploded = explode(':', $field);

            // Only allowed settings can be updated
            if ($category != $fieldExploded[0] || $scope != $fieldExploded[1]) {
                throw new ZetaException(409, 'Unable to update settings, allowed category-scope: $category-$scope, get fieldExploded[0]:$fieldExploded[1]');
            }
            $setting = self::updateSetting($fieldExploded[0], $fieldExploded[1], $fieldExploded[2], $value);

            $settingChanges = $setting->getChanges();
            if (! empty($settingChanges)) {
                ActivityController::createActivity($request->user(), $setting, 'Update', $settingChanges);
            }
        }

        return true;
    }

    public static function updateSetting($category, $scope, $field, $value)
    {
        $setting = self::getSetting($category, $scope, $field);
        $setting->update(['value' => $value]);

        return $setting;
    }

    public static function getSetting($category, $scope, $field)
    {
        return $setting = Setting::where([
            'category' => $category,
            'scope' => $scope,
            'field' => $field,
        ])->firstOrFail();
    }

    public static function getSettings($category, $scope = null, $field = null)
    {
        $settings = Setting::where(function ($query) use ($category, $scope, $field) {
            if ($category) {
                $query->where('category', $category);
            }
            if ($scope) {
                $query->where('scope', $scope);
            }
            if ($field) {
                $query->where('field', $field);
            }
        })->get();
        $settingValues = [];
        foreach ($settings as $setting) {
            $settingValues[$setting->category.':'.$setting->scope.':'.$setting->field] = $setting->value;
        }

        return $settingValues;
    }
}
