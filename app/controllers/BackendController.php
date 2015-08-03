<?php

class BackendController extends \BaseController {

    public function login() {
        try {
            $data = array(
                'mail' => Input::get('username'),
                'password' => Input::get('password')
            );

            if (! Auth::attempt($data)) {
                throw new Exception("Nie udało się");
            }

            $token = md5($data['mail'].$data['password'].'TAjnyKOT');
            $id = Auth::id();
            $name = Input::get('username');

            return Response::json(array(
                'token' => $token,
                'id' => $id,
                'name' => $name
            ));

        } catch (Exception $e) {
            Auth::logout();

            return Response::json(array('error' => $e->getMessage()));
        }

    }

    public function logout() {
        Auth::logout();

        return Response::json(array('success' => 1));
    }


}
