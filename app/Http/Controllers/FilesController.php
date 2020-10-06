<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\User;
use Intervention\Image\ImageManagerStatic as Image;


class FilesController extends Controller
{
    public function saveImageProfile(Request $request)
    {
        $file = $request->file;
        $randon = Str::random(10);
        $filename = Auth::id();
        $fileExt = explode('.',$file->getClientOriginalName());
        $fileserver = $randon.'_'.$filename.'.'.$fileExt[count($fileExt)-1];


        Storage::putFileAs('public/profile-images/', $file, $fileserver);

        $user = Auth::user();

        $user->photo = '/storage/profile-images/'.$fileserver;
        $user->save();

        $res = new \stdClass();
        $res->user = $user;
        $res->save = 'OK';
        return response()->json($res, 200);

    }
}
