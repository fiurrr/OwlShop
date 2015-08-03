<?php

class ProductController extends \BaseController
{
    public function index()
    {
        return Response::json(Products::all());
    }

    public function destroy($id) {
        try {
            if (!Auth::check()) {
                throw new Exception("Nie ma dostępu");
            } else {
                Products::destroy($id);

                return Response::json(array('success' => 1));
            }
        } catch(Exception $e) {
            return Response::json(array('error' => $e->getMessage()));
        }
    }

    public function product($id) {
        return Response::json(Products::where('id', '=', $id));
    }

}