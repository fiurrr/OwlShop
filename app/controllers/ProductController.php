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

    public function product($id) { return Response::json(Products::where('id', '=', $id)->first());  }

    public function update($id) {
        try {
            if(!Auth::check()) {
                throw new Exception("Nie ma dostepu");
            } else {
                $product = Products::find($id);

                $product->desc = Input::get('desc');
                $product->name = Input::get('name');
                $product->price = Input::get('price');

                $product->save();

                return Response::json(array('success' => 1));
            }
        } catch (Exception $e) {
            return Response::json(array('error' => $e->getMessage()));
        }
    }

    public function add() {
        try {
            if(!Auth::check()) {
                throw new Exception("Nie ma dostepu");
            } else {
                $product = new Products;

                $product->name = Input::get('name');
                $product->desc = Input::get('desc');
                $product->price = Input::get('price');
                $product->photo = 'okulary.jpg';
                $product->save();

                return Response::json(array('success' => 1));
            }
        } catch (Exception $e) {
            return Response::json(array('error' => $e->getMessage()));
        }
    }

}