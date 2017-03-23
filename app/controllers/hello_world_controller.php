<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('suunnitelmat/index.html');
    }

    public static function sandbox() {
        // Testaa koodiasi täällä
        View::make('helloworld.html');
    }

    public static function bethistory() {
        View::make('suunnitelmat/bethistory.html');
    }

    public static function bet() {
        View::make('suunnitelmat/bet.html');
    }
    
    public static function balance() {
        View::make('suunnitelmat/balance.html');
    }

}
