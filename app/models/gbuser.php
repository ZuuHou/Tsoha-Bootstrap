<?php

class Gbuser extends BaseModel {

    public $id, $username, $password, $balance;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_username', 'validate_password');
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Gbuser WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $gbuser = new Gbuser(array(
                'id' => $row['id'],
                'username' => $row['username'],
                'password' => $row['password'],
                'balance' => $row['balance']
            ));

            return $gbuser;
        }

        return null;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Gbuser (username, password, balance) VALUES (:username, :password, :balance) RETURNING id');
        $query->execute(array('username' => $this->username, 'password' => $this->password, 'balance' => $this->balance));
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    

    public function update_balance($balance) {
        $query = DB::connection()->prepare('UPDATE Gbuser SET balance = :balance WHERE id = :id');
        $query->execute(array('id' => $this->id, 'balance' => $balance));
    }

    public function validate_username() {
        $errors = array();
        if ($this->username == '') {
            $errors[] = 'Username cannot be empty!';
        }
        if (strlen($this->username) < 3) {
            $errors[] = 'Username should be atleast 3 characters long!';
        }

        return $errors;
    }

    public function validate_password() {
        $errors = array();
        if ($this->password == '') {
            $errors[] = 'Password cannot be empty!';
        }
        if (strlen($this->password) < 6) {
            $errors[] = 'Password should be atleast 6 characters long!';
        }

        return $errors;
    }

    public function authenticate($username, $password) {
        $query = DB::connection()->prepare('SELECT * FROM Gbuser WHERE username = :username AND password = :password LIMIT 1');
        $query->execute(array('username' => $username, 'password' => $password));
        $row = $query->fetch();
        if ($row) {
            return new Gbuser(array(
                'id' => $row['id'],
                'username' => $row['username'],
                'password' => $row['password'],
                'balance' => $row['balance']
            ));
        } else {
            return NULL;
        }
    }

}
