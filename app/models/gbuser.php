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
        // Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
        $query = DB::connection()->prepare('INSERT INTO Gbuser (username, password, balance) VALUES (:username, :password, :balance) RETURNING id');
        // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi
        $query->execute(array('username' => $this->username, 'password' => $this->password, 'balance' => $this->balance));
        // Haetaan kyselyn tuottama rivi, joka sisältää lisätyn rivin id-sarakkeen arvon
        $row = $query->fetch();
        // Asetetaan lisätyn rivin id-sarakkeen arvo oliomme id-attribuutin arvoksi
        $this->id = $row['id'];
    }

    public function validate_username() {
        $errors = array();
        if ($this->username == '' || $this->site == null) {
            $errors[] = 'Username cannot be empty!';
        }
        if (strlen($this->site) < 3) {
            $errors[] = 'Username should be atleast 3 characters long!';
        }

        return $errors;
    }

    public function validate_password() {
        if (strlen($this->site) < 6) {
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
