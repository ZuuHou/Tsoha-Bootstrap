<?php

class Ticket extends BaseModel {

    public $id, $gbuser_id, $site, $amount, $added, $odds, $result;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array();
    }

    public function all() {
        $query = DB::connection()->prepare('SELECT * FROM Ticket WHERE gbuser_id = :gbuser_id');
        $query->execute(array('gbuser_id' => $_SESSION['gbuser']));
        $rows = $query->fetchAll();
        $tickets = array();

        foreach ($rows as $row) {
            $ticket = new Ticket(array(
                'id' => $row['id'],
                'gbuser_id' => $row['gbuser_id'],
                'site' => $row['site'],
                'amount' => $row['amount'],
                'added' => $row['added']
            ));
            $ticket->calculateTotalOdds();
            $ticket->calculate_result();
            $tickets[] = $ticket;
        }
        return $tickets;
    }

    public static function show_open() {
        $query = DB::connection()->prepare('SELECT Ticket.id, Ticket.site, Ticket.amount, Ticket.added FROM Ticket INNER JOIN Betticket ON Betticket.ticket_id = Ticket.id INNER JOIN Bet ON Bet.id = Betticket.bet_id WHERE Bet.currentstate = 0 AND Ticket.gbuser_id = :gbuser_id');
        $query->execute(array('gbuser_id' => $_SESSION['gbuser']));
        $rows = $query->fetchAll();
        $tickets = array();

        foreach ($rows as $row) {
            $ticket = new Ticket(array(
                'id' => $row['id'],
                'site' => $row['site'],
                'amount' => $row['amount'],
                'added' => $row['added']
            ));
            $ticket->calculateTotalOdds();
            $tickets[] = $ticket;
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
            $ticket->calculateTotalOdds();
            return $ticket;
        }

        return null;
    }

    public static function save() {
        $query = DB::connection()->prepare('INSERT INTO Ticket (gbuser_id, site, amount, added) VALUES (:gbuser_id, :site, :amount, :added) RETURNING id');
        $query->execute(array('gbuser_id' => $this->gbuser_id, 'site' => $this->site, 'amount' => $this->amount, 'added' => $this->added));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public static function update($id) {
        $query = DB::connection()->prepare('UPDATE Ticket SET site = :site, amount = :amount WHERE id = :id');
        $query->execute(array('id' => $id, 'site' => $this->site, 'amount' => $this->amount));
        $row = $query->fetch();
        $this->id = $id;
    }

    public static function destroy($id) {
        $query = DB::connection()->prepare('DELETE FROM Ticket WHERE id = :id');
        $query->execute(array('id' => $id));
    }

    public function calculateTotalOdds() {
        $query = DB::connection()->prepare('SELECT Bet.odds FROM Bet INNER JOIN Betticket ON Betticket.bet_id = Bet.id WHERE Betticket.ticket_id = :id');
        $query->execute(array('id' => $this->id));
        $rows = $query->fetchAll();
        $odds = 1;

        foreach ($rows as $row) {
            $bets[] = new Bet(array(
                $odds = $odds * $row['odds']
            ));
        }
        $this->odds = $odds;
    }

    public function calculate_result() {
        $bets = Bet::findAllFromTicket($this->id);
        $lost = false;
        $open = false;
        foreach ($bets as $bet) {
            if ($bet->currentstate == 0) {
                $open = true;
            } else if ($bet->currentstate == 2) {
                $lost = true;
            }
        }
        if ($lost) {
            $this->result = $this->amount - 2 * $this->amount;
        } else if ($open) {
            $this->result = 0;
        } else {
            $this->result = $this->amount * $this->odds;
        }
    }      

    public static function validate_site() {
        $errors = array();
        if ($this->site == '' || $this->site == null) {
            $errors[] = 'Nimi ei saa olla tyhjä!';
        }
        if (strlen($this->site) < 3) {
            $errors[] = 'Nimen pituuden tulee olla vähintään kolme merkkiä!';
        }

        return $errors;
    }

    public static function validate_amount() {
        $errors = array();
        if (is_numeric($this->amount)) {
            $errors[] = 'Numero plaaplaa';
        }

        return $errors;
    }

}
