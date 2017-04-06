<?php

class UserController extends BaseController {

    public static function login() {
        View::make('gbuser/login.html');
    }
    
    public static function handle_login(){
    $params = $_POST;

    $gbuser = Gbuser::authenticate($params['username'], $params['password']);

    if(!$gbuser){
      View::make('gbuser/login.html', array('error' => 'Wrong username or password, please try again...', 'username' => $params['username']));                                           
    }else{
      $_SESSION['gbuser'] = $gbuser->id;

      Redirect::to('/', array('message' => 'Welcome back to your GreenBook  ' . $gbuser->username . '!'));
    }
  }

    public static function create() {
        View::make('gbuser/newaccount.html');
    }

    public static function store() {

        $params = $_POST;

        $gbuser = new Gbuser(array(
            'username' => $params['username'],
            'password' => $params['password'],
            'balance' => NULL
        ));

        $gbuser->save();

        Redirect::to('/ticket/' . $gbuser->id, array('message' => 'You have succesfully created a new useraccount!!'));
    }

}
