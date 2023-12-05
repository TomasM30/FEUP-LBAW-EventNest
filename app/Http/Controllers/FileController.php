<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{   
    static $default = 'default.png';
    static $diskName = 'lbaw23144';

    static $systemTypes = [
        'profile' => ['png', 'jpg', 'jpeg', 'gif'],
        'event' => ['png', 'jpg', 'jpeg', 'gif'],
        'report' => ['png', 'jpg', 'jpeg', 'gif', 'MP4']
    ];

    private static function isValidType(String $type) {
        return array_key_exists($type, self::$systemTypes);
    }
    
    private static function defaultAsset(String $type) {
        return asset($type . '/' . self::$default);
    }
    
    private static function getFileName (String $type, int $id) {
            
        $fileName = null;
        switch($type) {
            case 'profile':
                $fileName = User::find($id)->profile_image;
                break;
            case 'event':
                $fileName = Event::find($id)->image;
                break;
            case 'report':
                $fileName = Report::find($id)->file;
                break;
            }
    
        return $fileName;
    }
    
    static function get(String $type, int $userId) {
        
        // Validation: upload type
        if (!self::isValidType($type)) {
            return self::defaultAsset($type);
        }
    
        // Validation: file exists
        $fileName = self::getFileName($type, $userId);
        if ($fileName) {
            return asset($type . '/' . $fileName);
        }
    
        // Not found: returns default asset
        return self::defaultAsset($type);
    }

    function upload(Request $request) {

        log::debug($request);
        $file = $request->file('file');
        $type = $request->type;
        $id = $request->id;
        $extension = $file->getClientOriginalExtension();
        if (!in_array($extension, self::$systemTypes[$type])) {
            return ['file' => 'Invalid file type.'];
        }
          
        $fileName = $file->hashName();
    
        // Save in correct folder and disk
        $request->file->storeAs($type, $fileName, self::$diskName);

        if ($type === 'event') {
            $event = Event::find($id);
            if ($event) {
                $event->image = $fileName;
                $event->save();
            }
            return redirect()->route('events.details', ['id' => $id]);
        } else if ($type === 'profile') {
            $user = User::find($id);
            if ($user) {
                $user->profile_image = $fileName;
                $user->save();
            }
        }

        return redirect()->back();
    }
    
    
}

