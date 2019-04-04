<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Lugar;
use App\Visita;
use Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Image;
//use GuzzleHttp\Exception\GuzzleException;
//use GuzzleHttp\Client;

class ExternosController extends Controller
{
    public function anyIdentificarse(Request $request)
    {
        $usuario = User::where('email', $request->input('email'))->first();
		if (Hash::check($request->input('password'), $usuario->password)) {
		    // The passwords match...
		} else {
			$usuario = null;
		}
        return response()->json(['usuario' => $usuario]);
    }

	public function anyAuthenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $customClaims = array('exp' => strtotime("+10 days"));

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials, $customClaims)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }

    public function anyRegistrate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $customClaims = array('exp' => strtotime("+10 days"));

        $usuario = User::where('email', $request->input('email'))->first();
        if((empty(!$usuario)) and (empty($request->input('fb')))) {
            return response()->json(['error' => 'user_duplicate'], 401);            
        }
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials, $customClaims)) {
                //crear usuario ya que NO existe
                //return response()->json(['error' => 'invalid_credentials'], 401);
                $usuario = new User();
                $usuario->name = $request->input('name');
                $usuario->email = $request->input('email');
                $usuario->password = Hash::make($request->input('password'));
                $usuario->save();

                $lugar = new Lugar();
                $lugar->user_id = $usuario->id;
                $lugar->nombre  = $request->input('name');
                $lugar->slug    = str_slug($request->input('name'), '-').str_random(3);
                $lugar->estado  = 1;
                $lugar->save();
                //lo tengo que loguear
                try {
                    if (! $token = JWTAuth::attempt($credentials, $customClaims)) {
                        return response()->json(['error' => 'invalid_credentials'], 401);
                    }
                } catch (JWTException $e) {
                    return response()->json(['error' => 'could_not_create_token'], 500);
                }
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }

    public function anyCuenta(Request $request)
    {
        $token = $_POST['token'];
        $usuario = JWTAuth::authenticate($token);
        unset($usuario->id);
        unset($usuario->fcm_token);
        unset($usuario->created_at);
        unset($usuario->updated_at);
        //return response()->json(['usuario' => $usuario]);
        return $usuario;
    }

    public function anyLugares(Request $request)
    {
        $usuario = JWTAuth::authenticate($_POST['token']);
        $lugares = $usuario->lugares()->orderBy('id', 'desc')->get();
        return response()->json(['lugares' => $lugares]);
    }

    public function getVisita(Request $request, $slug = null)
    {
        $lugar = Lugar::where('slug', $slug)->with('usuario')->first();
        $referencia = generateRandomString(18);
        return view('visita', ['lugar'=>$lugar, 'referencia'=>$referencia]);
    }

    public function anyTimbre(Request $request, $id = null)
    {
        $lugar = Lugar::find($id);

        if($lugar) {
            $usuario = $lugar->usuario;
            $fcm_token = $usuario->fcm_token;
            $visitante = $request->input('remitente');
            $referencia = $request->input('referencia').'_'.$request->ip();
            $visita = Visita::where('referencia', $referencia)->where('lugar_id', $lugar->id)->orderBy('id', 'desc')->first();
            $max_llamadas = 10;

            if ($visita) {
                if  ($visita->llamadas <= $max_llamadas) {
                    $visita->llamadas += 1;
                    $visita->save();
                }
            } else {
                $visita = new Visita();
                $visita->lugar_id   = $lugar->id;
                $visita->referencia = $referencia;
                $visita->estado     = 1;
                $visita->texto      = $visitante;                
                $visita->llamadas   = 1;
                $visita->save();
            }

            $key = 'key=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
            $to=$fcm_token;
            $title="Timbre";
            $message="Chequin de ".$visitante." en ".$lugar->nombre;
            $registrationIds = array($to);
            $msg = array (
                'message' => $message,
                'title' => $title,
                'vibrate' => 1,
                /*'sound' => 'default',*/
                /*'sound' => 'buzzer',*/
                'sound' => 'ringtone',
                'content-available' => 1,
                'force-start' => 1,
                'no-cache' => 1,
                'notId' => $request->input('referencia'),
                'lugar_id' => $id,
                'referencia' => $referencia
            );
            $fields = array (
                //'registration_ids' => $registrationIds,
                'to' => $fcm_token,
                'data' => $msg,
    			'android' => array(
    				'priority' => 'high'
    			),
    			'priority' => 2
            );
            $headers = array (
                'Authorization: ' . $key,
                'Content-Type: application/json'
            );
            $ch = curl_init();
            //curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($ch );

            return response()->json(['res'=>$result, 'fields'=>json_encode($fields)]);
        } else {
            return response()->json(['error' => true]);
        }


    }

    public function anyFcm(Request $request)
    {
        $usuario = JWTAuth::authenticate($_POST['token']);
        $usuario->fcm_token = $_POST['fcm_token'];
        $usuario->save();
        return response()->json(['estado' => 1]);
    }

    public function anyTimbreupdate(Request $request)
    {
        $usuario = JWTAuth::authenticate($_POST['token']);

        if(!empty($request->input('id'))) {
            $lugar = Lugar::find($request->input('id'));

            $lugar_existente = Lugar::where('slug', $request->input('nombre'))->where('user_id', '!=', $usuario->id)->get();
            if ($lugar_existente->count() > 0) return response()->json(['error' => true]);

        } else {
            $lugar_existente = Lugar::where('slug', $request->input('nombre'))->get();
            if ($lugar_existente->count() > 0) return response()->json(['error' => true]);
            $lugar = new Lugar();
        }

        $lugar->user_id  = $usuario->id;
        $lugar->nombre  = $request->input('nombre');
        $lugar->estado  = $request->input('estado');
        $lugar->slug    = str_slug($request->input('nombre'), '-');
        /*
        $file = $request->file('imagen');
        if (!empty($file)) {
            $destinationPath = base_path() . '/public/cdn/img/';
            if (is_file($destinationPath.$obj->img_path)) unlink($destinationPath.$obj->img_path);
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $newname = str_random(16).'.'.$extension;
            $file->move($destinationPath, $newname);
            //$thumb = Image::make($destinationPath.$newname)->resize(200,200)->save($destinationPath.$newname);
            $lugar->imagen  = $newname;
        }
        */
        $lugar->save();
        $estado = true;
        return response()->json(['lugar' => $lugar]);
    }

    public function anyTimbredelete(Request $request)
    {
        $estado = false;
        $usuario = JWTAuth::authenticate($_POST['token']);
        $lugar = Lugar::where('id', $request->input('id'))->where('user_id', $usuario->id)->first();
        if (empty($lugar)) return response()->json(['error' => true]);
        $lugar->delete();
        $estado = true;
        return response()->json(['estado' => $estado]);
    }

    public function anyHistorial(Request $request)
    {
        $usuario = JWTAuth::authenticate($_POST['token']);
        $visitas = $usuario->visitas()->orderBy('id', 'desc')->get();
        return response()->json(['visitas' => $visitas]);
    }

    public function anyResponder(Request $request)
    {
        $usuario = JWTAuth::authenticate($_POST['token']);
        $lugares = $usuario->lugares()->get();
        $visita = Visita::where('referencia', $request->input('referencia'))->first();

        $validado = $lugares->where('id', $visita->id);
        $estado = false;
        if($validado) {
            $visita->respuesta = $request->input('respuesta');
            $visita->estado = 2;
            $visita->save();
            $estado = true;
        }
        return response()->json(['estado' => $estado]);
    }

    public function anyRespuesta(Request $request)
    {
        $referencia = $request->input('referencia').'_'.$request->ip();
        $visita = Visita::where('referencia', $referencia)->first();
        $rta = ["estado"=>false, "respuesta"=>"Sin Responder"];
        if (($visita) && ($visita->estado==2)) {
            $rta = ["estado"=>true, "respuesta"=>$visita->respuesta];
        }
        return response()->json($rta);
    }

    public function anyPrivacidad(Request $request)
    {
        return view('privacidad');
    }
}
