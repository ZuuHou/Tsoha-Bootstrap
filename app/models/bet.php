<?php

class Bet extends BaseModel {

    public $id, $sport, $event, $endresult, $odds, $currentstate, $eventdate;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_odds');
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Bet (sport, event, endresult, odds, eventdate) VALUES (:sport, :event, :endresult, :odds, :eventdate) RETURNING id');
        $query->execute(array('sport' => $this->sport, 'event' => $this->event,
            'endresult' => $this->endresult,
            'odds' => $this->odds,
            'eventdate' => $this->eventdate));
        $row = $query->fetch();
        $this->id = $row['id'];
        return $this->id;
    }

    public function update($id) {
        $query = DB::connection()->prepare('UPDATE Bet SET sport = :sport, event = :event, endresult = :endresult, odds = :odds, currentstate = :currentstate, eventdate = :eventdate WHERE id = :id');
        $query->execute(array('id' => $id, 'sport' => $this->sport, 'event' => $this->event, 'endresult' => $this->endresult, 'odds' => $this->odds, 'currentstate' => $this->currentstate, 'eventdate' => $this->eventdate));
        $this->id = $id;
    }

    public function update_currentstate($id) {
        $query = DB::connection()->prepare('UPDATE Bet SET currentstate = :currentstate WHERE id = :id');
        $query->execute(array('currentstate' => $this->currentstate, 'id' => $id));
        $this->id = $id;
    }

    public function destroy() {
        $this->remove_from_betticket($this->id);
        $query = DB::connection()->prepare('DELETE FROM Bet WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

    public function remove_from_betticket($id) {
        $query = DB::connection()->prepare('DELETE FROM BetTicket WHERE bet_id = :id');
        $query->execute(array('id' => $id));
    }

    public function find_ticket_id($bet_id) {
        $query = DB::connection()->prepare('SELECT * FROM BetTicket WHERE bet_id = :bet_id');
        $query->execute(array('bet_id' => $bet_id));
        $row = $query->fetch();
        $id = $row['ticket_id'];
        return $id;
    }

    public static function find_all_from_ticket($id) {
        $query = DB::connection()->prepare('SELECT * FROM Bet INNER JOIN Betticket ON Betticket.bet_id = Bet.id WHERE Betticket.ticket_id = :id');
        $query->execute(array('id' => $id));
        $query->execute();
        $rows = $query->fetchAll();
        $bets = array();

        foreach ($rows as $row) {
            $bets[] = new Bet(array(
                'id' => $row['id'],
                'sport' => $row['sport'],
                'event' => $row['event'],
                'endresult' => $row['endresult'],
                'odds' => $row['odds'],
                'currentstate' => $row['currentstate'],
                'eventdate' => $row['eventdate']
            ));
        }
        return $bets;
    }

    public function validate_odds() {
        $errors = array();
        if (!$this->validate_number($this->odds)) {
            $errors[] = 'Your odds is not a valid number.';
        }
        if ($this->odds <= 1) {
            $errors[] = 'Odds must be over 1.0!';
        }

        return $errors;
    }

}
