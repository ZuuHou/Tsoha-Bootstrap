<?php

  class BaseController{

    public static function get_user_logged_in(){
    if(isset($_SESSION['gbuser'])){
      $user_id = $_SESSION['gbuser'];
      $gbuser = Gbuser::find($user_id);

      return $gbuser;
    }

    return null;
  }

    public static function check_logged_in(){
      if(self::get_user_logged_in() === null) {
          Redirect::to('/gbuser/login', array('message' => ''));
      }
    }

  }
