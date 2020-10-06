<?php

namespace App\Http\Controllers\Auth\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;
use App\User;
use App\Client;
use App\Executive;
use Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;
use DateTime;
use Carbon\Carbon;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('session.expire');
    }
    protected function indexUsers($users){
        foreach ($users as $user){
            $dateTime= Carbon::now();
            $session_expire_on = $user->session_expire_on;
            //verificamos si hay session abierta ..
            if( $user->status == true){
                //verificamos si no expiro la session..
                if( $session_expire_on > $dateTime ){
                    $user->status = "Online";
                    $user->session_expire_on = $session_expire_on;
                //Si expira la session..
                }else{
                    $user->status = "Offline";
                    $user->session_expire_on = $session_expire_on;
                }
            //Si esta cerrada ..
            }else{
                $user->status = "Offline";
                $user->session_expire_on = "N/A";
            }

        }
        return $users;
    }
    public function pagination($orderName = 'id', $order = 'asc')
    {
        $this->middleware('isAdmin');
        $this->middleware('onlyAjax');

        $users = User::whereNotIn('id', [auth()->id(), 1])->orderBy($orderName, $order)->paginate(15);
        //return Response::json($users, 200);
        $this->indexUsers($users);
        return Response::json($users, 200);
    }

    public function search(Request $request, $orderName = 'id', $order = 'asc')
    {
        $this->middleware('isAdmin');

        $users = User::Where([
            ['email', 'like', $request->text."%"],
            ['id', '!=', 1],
            ['id', '!=', auth()->id()]
            ])
        ->orWhere([
            ['name', 'like', $request->text."%"],
            ['id', '!=', 1],
            ['id', '!=', auth()->id()]
            ])
        ->orderBy($orderName, $order)
        ->paginate(15);
        // ->except([1, auth()->id()]);

        $this->indexUsers($users);
        return Response::json($users, 200);
    }

    public function index()
    {
        $this->middleware('isAdmin');

        $users = User::whereNotIn('id', [auth()->id(), 1]);
        //return Response::json($users, 200);
        return view('auth.user.index');
        //return view('auth.user.index',compact($users));

    }

    public function store(Request $request)
    {

        $res = new \stdClass(); //Respuesta en JSON

        $validator =  Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if($validator->fails()){

            $res->save = 'error';
            $res->errors = $validator->errors();

            return Response::json($res, 200);
        }

            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'is_admin' => $request['is_admin'],
                'status' => false,
            ]);

        $res->save = 'OK';
        $res->user = $user;

        return Response::json($res, 200);
    }

    public function edit($id)
    {
        $this->middleware('isAdmin');
        $user = User::find($id);
        return view('auth.edit')->with('user', $user);
    }

    public function myprofile()
    {
        $user = Auth::user();
        return view('auth.user.edit')->with('user', $user);
    }

    public function myphoto(Request $request)
    {
        $this->validate($request, [
            'photo' => 'required|image|mimes:jpeg,png,jpg'
        ]);

        $res = new \stdClass(); //Respuesta en JSON

        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $fileName = auth()->id() . '.' . $extension;
        $path = public_path('img/users/'.$fileName);

        Image::make($file)->fit(144, 144)->save($path);

        $user = auth()->user();
        $user->photo = $extension;
        $saved = $user->save();

        $data['success'] = $saved;
        $data['path'] = $user->getAvatarUrl() . '?' . uniqid();

        return $data;
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);
        $res = new \stdClass(); //Respuesta en JSON
        //Si $request tiene password

        if($request['password'] != "" || $request['password'] != null ){

            $rules = array (
                'name'       => 'required',
                'email'      => 'unique:users,email,'.$user->id,
                'password'   => 'required|min:8'
            );

            $validator = Validator::make($request->all(),$rules);

            if($validator->fails()){

                $res->save = 'error';
                $res->errors = $validator->errors();
                $res->id = $user->id;

                return Response::json($res, 200);

            }

            //actualizamos datos
            $user->name = request('name');
            $user->email = request('email');
            $user->password = bcrypt(request('password'));
            if($user->id != Auth::user()->id){
                if($request['is_admin']){
                    $user->is_admin = true;
                }else{
                    $user->is_admin = false;
                }
            }
            $user->save();

            $res->save = 'OK';
            $res->user = $user;

            return Response::json($res, 200);
            //return back()->with('status', 'Usuario actualizado Satisfactoriamente')->with('user', $user);
        }

        //Si no hay password
        $rules = array (
            'name'  => 'required',
            'email' => 'unique:users,email,'.$user->id,
        );

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){

            $res->save = 'error';
            $res->errors = $validator->errors();
            $res->id = $user->id;
            // return response()->json(['errors'=>$validator->errors()]);
            return Response::json($res, 200);
        }

        //actualizamos datos
        $user->name = request('name');
        $user->email = request('email');
        if($user->id != Auth::user()->id){
            if($request['is_admin']){
                $user->is_admin = true;
            }else{
                $user->is_admin = false;
            }
        }
        $user->save();

            // $dateTime= Carbon::now();
            // $session_expire_on =  $user->session_expire_on;
            // //verificamos si hay session abierta ..
            // if( $user->status == true){
            //     //verificamos si no expiro la session..
            //     if( $session_expire_on > $dateTime ){
            //         $user->status = "Online";
            //         $user->session_expire_on = $session_expire_on;
            //     //Si expira la session..
            //     }else{
            //         $user->status = "Offline";
            //         $user->session_expire_on = "Cierre de sesión incorrecto";
            //     }
            // //Si esta cerrada ..
            // }else{
            //     $user->status = "Offline";
            //     $user->session_expire_on = "N/A";
            // }

        $res->save = 'OK';
        $res->user = $user;

        return Response::json($res, 200);

        //return back()->with('status', 'Usuario '.$user->name.' actualizado Satisfactoriamente')->with('user', $user);

    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        if($id == Auth::user()->id){
            Log::alert('No permitido, estas intentando eliminar tu usuario');

            return redirect()->route('users.index')->with('warning', 'No permitido, estas intentando eliminar tu usuario');
        }

        $user = User::find($id);

            $user->delete();

            $res = new \stdClass(); //Respuesta en JSON

            $res->status = 'OK';
            $res->message = 'Usuario eliminado satisfactoriamente';
            $res->id = $id;
            return Response::json($res, 200);
    }
}
