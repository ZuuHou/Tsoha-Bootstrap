<?php

class Ticket extends BaseModel {

    public $id, $gbuser_id, $site, $amount, $added, $odds, $result, $closingdate;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_amount', 'validate_site');
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
            $ticket->amount = $ticket->format_decimals($ticket->amount);
            $ticket->calculateTotalOdds();
            $ticket->calculate_result();
            $tickets[] = $ticket;
        }
        return $tickets;
    }

    public function show_open() {
        $query = DB::connection()->prepare('SELECT DISTINCT Ticket.id, Ticket.site, Ticket.amount FROM Ticket INNER JOIN Betticket ON Betticket.ticket_id = Ticket.id INNER JOIN Bet ON Bet.id = Betticket.bet_id WHERE Bet.currentstate = 0 AND Ticket.gbuser_id = :gbuser_id');
        $query->execute(array('gbuser_id' => $_SESSION['gbuser']));
        $rows = $query->fetchAll();
        $tickets = array();

        foreach ($rows as $row) {
            $ticket = new Ticket(array(
                'id' => $row['id'],
                'site' => $row['site'],
                'amount' => $row['amount']
            ));
            $ticket->amount = $ticket->format_decimals($ticket->amount);
            $ticket->calculateTotalOdds();
            $ticket->find_closing_date();
            $tickets[] = $ticket;
        }
        return $tickets;
    }

    public function find($id) {
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
            $ticket->amount = $ticket->format_decimals($ticket->amount);
            $ticket->calculateTotalOdds();
            return $ticket;
        }

        return null;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Ticket (gbuser_id, site, amount) VALUES (:gbuser_id, :site, :amount) RETURNING id');
        $query->execute(array('gbuser_id' => $this->gbuser_id, 'site' => $this->site, 'amount' => $this->amount));
        $row = $query->fetch();
        $this->id = $row['id'];
        return $this->id;
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Ticket SET site = :site, amount = :amount WHERE id = :id');
        $query->execute(array('id' => $this->id, 'site' => $this->site, 'amount' => $this->amount));
    }

    public function destroy($id) {
        $this->remove_from_betticket($id);
        $query = DB::connection()->prepare('DELETE FROM Ticket WHERE id = :id');
        $query->execute(array('id' => $id));
    }

    public function remove_from_betticket($id) {
        $query = DB::connection()->prepare('DELETE FROM BetTicket WHERE ticket_id = :id');
        $query->execute(array('id' => $id));
    }

    public function add_bet($id) {
        $query = DB::connection()->prepare('INSERT INTO BetTicket (ticket_id, bet_id) VALUES (:ticket_id, :bet_id)');
        $query->execute(array('ticket_id' => $this->id, 'bet_id' => $id));
        $this->calculateTotalOdds();
    }

    public function find_closing_date() {
        $query = DB::connection()->prepare('SELECT Bet.eventdate FROM Bet INNER JOIN Betticket ON Betticket.bet_id = Bet.id WHERE Betticket.ticket_id = :id ORDER BY Bet.eventdate DESC LIMIT 1');
        $query->execute(array('id' => $this->id));
        $row = $query->fetch();
        $this->closingdate = $row[0];
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
        $this->odds = $this->format_decimals($odds);
    }

    public function calculate_result() {
        $bets = Bet::find_all_from_ticket($this->id);
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
            $this->result = $this->format_decimals($this->amount - 2 * $this->amount);
        } else if ($open) {
            $this->result = 0.00;
        } else {
            $this->result = $this->format_decimals($this->amount * $this->odds - $this->amount);
        }
    }

    public function calculateTotalResult($tickets) {
        $total = 0;
        foreach ($tickets as $ticket) {
            $total = $ticket->format_decimals($total + $ticket->result);
        }
        return $total;
    }

    public function validate_site() {
        $errors = array();
        if ($this->site == '' || $this->site == null) {
            $errors[] = 'Your site cannot be empty!';
        }
        if (strlen($this->site) < 3) {
            $errors[] = 'Your site must be atleast 3 characters long!';
        }

        return $errors;
    }

    public function validate_amount() {
        $errors = array();
        if (!$this->validate_number($this->amount)) {
            $errors[] = 'Your amount is not a valid number.';
        }

        if ($this->amount <= 0) {
            $errors[] = 'Amount must be bigger than 0!';
        }

        return $errors;
    }

    public static function format_decimals($number) {
        return number_format((float) $number, 2, '.', '');
    }

    public function check_if_open() {
        $query = DB::connection()->prepare('SELECT COUNT(BetTicket.bet_id) FROM Ticket INNER JOIN Betticket ON Betticket.ticket_id = Ticket.id INNER JOIN Bet ON Bet.id = Betticket.bet_id WHERE Bet.currentstate = 0 AND Ticket.gbuser_id = :gbuser_id AND Ticket.id = :id');
        $query->execute(array('gbuser_id' => $_SESSION['gbuser'], 'id' => $this->id));
        $row = $query->fetch();
        if ($row[0] < 1) {
            return false;
        } else {
            return true;
        }
    }

    public function check_if_no_events() {
        $query = DB::connection()->prepare('SELECT COUNT(BetTicket.bet_id) FROM Ticket INNER JOIN Betticket ON Betticket.ticket_id = Ticket.id  WHERE Ticket.gbuser_id = :gbuser_id AND Ticket.id = :id');
        $query->execute(array('gbuser_id' => $_SESSION['gbuser'], 'id' => $this->id));
        $row = $query->fetch();
        if ($row[0] < 1) {
            $this->destroy($this->id);
        }
    }

}
