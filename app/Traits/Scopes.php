<?php

namespace App\Traits;

trait Scopes
{
    public static function scopeUploadImage($request,$path){
        $name = time() . $request->file('image')->getClientOriginalName();
        $request->file('image')->storeAs($path,$name);
        return $name;
    }

    public static function scopeFileExists($path,$name){
        if (file_exists(storage_path('app/public/'.$path.'/'.$name))) {
            unlink(storage_path('app/public/'.$path.'/'.$name));
        }
    }
}
