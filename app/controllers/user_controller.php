<?php

class UserController extends BaseController {

    public static function login() {
        View::make('gbuser/login.html');
    }
    
    public static function logout(){
    $_SESSION['gbuser'] = null;
    Redirect::to('/gbuser/login', array('message' => 'You have logged out!'));
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
    
    public function deposit() {
        View::make('gbuser/deposit.html');
    }
    
    public function withdraw() {
        View::make('gbuser/withdraw.html');
    }
    
    public function update_balance() {
        self::check_logged_in();
        $params = $_POST;
        if($params['deposit'] == null) {
            $gbuser = new Gbuser(array(
                'username' => $_SESSION['gbuser'],
                'balance' => user_logged_in.balance - $params['withdraw']
            ));
        }
        if($params['withdraw'] == null) {
            $gbuser = new Gbuser(array(
                'username' => $_SESSION['gbuser'],
                'balance' => user_logged_in.balance + $params['deposit']
            ));
        }
        $gbuser->update_balance();
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
