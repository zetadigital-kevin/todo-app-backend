<?php
namespace App\Http\Controllers\Base\Core;
use App\Http\Controllers\Controller;
use App\Http\Models\Activity;
use Spatie\Permission\Models\Role;

class ActivityController extends Controller
{
    public static function createActivity($trigger, $target, $action, $optional = null)
    {
        $newActivity = new Activity([
            'model_type_1' => is_null($trigger) ? null : \get_class($trigger->getModel()),
            'model_id_1' => is_null($trigger) ? null : $trigger->id,
            'model_type_2' => is_null($target) ? null : \get_class($target->getModel()) ,
            'model_id_2' => is_null($target) ? null : $target->id,
            'action' => $action,
            'description' => self::generateDescription($trigger, $target, $action, $optional)
        ]);
        $newActivity->save();
        return $newActivity;
    }

    private static function generateDescription($trigger, $target, $action, $optional){
        // Process trigger
        if (is_null($trigger)){
            $triggerDesc = 'System';
        }
        else {
            if ($trigger instanceof Role) {
                $triggerDesc = $trigger->display_name;
            }
            else {
                $triggerDesc = $trigger->toDescription();
            }
        }

        // Process target
        $targetDesc = '';
        if (!is_null($target)) {
            if ($target instanceof Role) {
                $targetDesc = $target->display_name;
            }
            else {
                $targetDesc = $target->toDescription();
            }
        }

        // Process action
        switch ($action) {
            case 'Create':
                $description = $triggerDesc . ' created ' . $targetDesc;
                break;
            case 'Login':
                $description = $triggerDesc . ' logged in';
                break;
            case 'Logout':
                $description = $triggerDesc . ' logged out';
                break;
            case 'Activate':
                $description = $triggerDesc . ' activated ' . $targetDesc;
                break;
            case 'Inactivate':
                $description = $triggerDesc . ' inactivated ' . $targetDesc;
                break;
            case 'Delete':
                $description = $triggerDesc . ' delete ' . $targetDesc;
                break;
            case 'Update':
                $description = $triggerDesc . ' updated ';
                if (!empty($optional)){
                    foreach ($optional as $updatedField => $updatedValue){
                        if ($updatedField == 'password'){
                            $description .= "$updatedField ";
                        }
                        else if ($updatedField == 'updated_at'){
                            continue;
                        }
                        else {
                            $description .= "$updatedField to $updatedValue; ";
                        }
                    }
                } else {
                    $description .= "nothing";
                }
                break;
            default:
                $description =  "unknown action";
        }

        return $description;
    }
}
