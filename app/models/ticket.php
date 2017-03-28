<?php

class Ticket extends BaseModel {

    public $id, $gbuser_id, $site, $amount, $currentstate, $added;

    public function __construct($attributes) {
        parent::__construct($attributes);
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
                'currentstate' => $row['currentstate'],
                'added' => $row['added']
            ));
        }

        return $tickets;
    }
    
      public static function find($id){
    $query = DB::connection()->prepare('SELECT * FROM Ticket WHERE id = :id LIMIT 1');
    $query->execute(array('id' => $id));
    $row = $query->fetch();

    if($row){
      $ticket = new Ticket(array(
        'id' => $row['id'],
        'gbuser_id' => $row['gbuser_id'],
        'site' => $row['site'],
        'amount' => $row['amount'],
        'currentstate' => $row['currentstate'],
        'added' => $row['added']
      ));

      return $ticket;
    }

    return null;
  }
  
 public function save(){
    // Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
    $query = DB::connection()->prepare('INSERT INTO Ticket (site, amount, currentstate, added) VALUES (:site, :amount, :currentstate, :added) RETURNING id');
    // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi
    $query->execute(array('site' => $this->site, 'amount' => $this->amount, 'currentstate' => $this->currentstate, 'added' => $this->added));
    // Haetaan kyselyn tuottama rivi, joka sisältää lisätyn rivin id-sarakkeen arvon
    $row = $query->fetch();
    // Asetetaan lisätyn rivin id-sarakkeen arvo oliomme id-attribuutin arvoksi
    $this->id = $row['id'];
  }
}
