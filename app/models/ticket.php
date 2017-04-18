<?php

class Ticket extends BaseModel {

    public $id, $gbuser_id, $site, $amount, $added;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_site', 'validate_amount');
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Ticket');
        $query->execute();
        $rows = $query->fetchAll();
        $tickets = array();

        foreach ($rows as $row) {
            $tickets[] = new Ticket(array(
                'id' => $row['id'],
                'gbuser_id' => $row['gbuser_id'],
                'site' => $row['site'],
                'amount' => $row['amount'],
                'added' => $row['added']
            ));
        }

        return $tickets;
    }
    
    public static function show_open() {
        $query = DB::connection()->prepare('SELECT Ticket.id, Ticket.site, Ticket.amount, Ticket.added FROM Ticket INNER JOIN Betticket ON Betticket.ticket_id = Ticket.id INNER JOIN Bet ON Bet.id = Betticket.bet_id WHERE Bet.currentstate = 0 AND Ticket.gbuser_id = 1');
//        $query->execute(array('gbuser_id' => $_SESSION['gbuser']));
        $query->execute();
        $rows = $query->fetchAll();
        $tickets = array();

        foreach ($rows as $row) {
            $tickets[] = new Ticket(array(
                'id' => $row['id'],
                'gbuser_id' => '1',
                'site' => $row['site'],
                'amount' => $row['amount'],
                'added' => $row['added']
            ));
        }
        return $tickets;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Ticket WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $ticket = new Ticket(array(
                'id' => $row['id'],
                'gbuser_id' => $row['gbuser_id'],
                'site' => $row['site'],
                'amount' => $row['amount'],
                'added' => $row['added']
            ));

            return $ticket;
        }

        return null;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Ticket (gbuser_id, site, amount, added) VALUES (:gbuser_id, :site, :amount, :added) RETURNING id');
        $query->execute(array('gbuser_id' => $this->gbuser_id,  'site' => $this->site, 'amount' => $this->amount, 'added' => $this->added));
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
        public function update($id) {
        $query = DB::connection()->prepare('UPDATE Ticket SET site = :site, amount = :amount, added = :added WHERE id = :id');
        $query->execute(array('id' => $id, 'site' => $this->site, 'amount' => $this->amount, 'added' => $this->added));
        $row = $query->fetch();
        $this->id = $id;
    }
    
        public function destroy($id) {
        $query = DB::connection()->prepare('DELETE FROM Ticket WHERE id = :id');
        $query->execute(array('id' => $id));
    }

    public function validate_site() {
        $errors = array();
        if ($this->site == '' || $this->site == null) {
            $errors[] = 'Nimi ei saa olla tyhjä!';
        }
        if (strlen($this->site) < 3) {
            $errors[] = 'Nimen pituuden tulee olla vähintään kolme merkkiä!';
        }

        return $errors;
    }

    public function validate_amount() {
        $errors = array();
        if (is_numeric($this->amount)) {
            $errors[] = 'Numero plaaplaa';
        }

        return $errors;
    }

}
