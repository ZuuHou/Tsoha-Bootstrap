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

    public static function bet1() {
        View::make('suunnitelmat/bet1.html');
    }
    
        public static function bet2() {
        View::make('suunnitelmat/bet2.html');
    }
    
    public static function balance() {
        View::make('suunnitelmat/balance.html');
    }

}
