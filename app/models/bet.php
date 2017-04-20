<?php

class Bet extends BaseModel {

    public $id, $sport, $event, $endresult, $odds, $currentstate, $eventdate;

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Bet (sport, event, endresult, odds, currentstate, eventdate) VALUES (:sport, :event, :endresult, :odds, :currenstate, :eventdate) RETURNING id');
        $query->execute(array('sport' => $this->sport, 'event' => $this->event, 'endresult' => $this->endresult, 'odds' => $this->odds, 'currentstate' => $this->currentstate, 'eventdate' => $this->eventdate));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update($id) {
        $query = DB::connection()->prepare('UPDATE Bet SET sport = :sport, event = :event, endresult = :endresult, odds = :odds, currentstate = :currentstate, eventdate = :eventdate WHERE id = :id');
        $query->execute(array('id' => $id, 'sport' => $this->sport, 'event' => $this->event, 'endresult' => $this->endresult, 'odds' => $this->odds, 'currentstate' => $this->currentstate, 'eventdate' => $this->eventdate));
        $row = $query->fetch();
        $this->id = $id;
    }

    public function destroy($id) {
        $query = DB::connection()->prepare('DELETE FROM Bet WHERE id = :id');
        $query->execute(array('id' => $id));
    }

    public static function findAllFromTicket($id) {
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

}
