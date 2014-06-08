<?php

class GamesController extends GamesAppController
{
    
    private $head;
    private $tail;
    private $odds;

    public function beforeFilter() {

        parent::beforeFilter();

        $this->head = 0;            // DO NOT CHANGE
        $this->tail = 0;            // DO NOT CHANGE
        $this->odds = 49;           // PERCENTAGE CHANCE OF WINNING YOU CAN CHANGE THIS

    }

    public function index() {

        $bet = "head";       

        $response = array("success" => true, "code" => 200);

        $response['game'] = $this->tossCoin($_GET["bet"], $this->gameOdds($this->odds), $_GET['amount']);

        $this->set(array(
            'response' => $response,
            '_serialize' => array('response')
        ));

    }

    public function add() {}

    public function edit($id = null) {}

    public function delete($id = null) {}

    public function view($id = null) {}

    private function gameOdds($odd = null) {
        return (50 - $odd);
    }

    private function tossCoin($bet = null, $odd = 0, $amount) {

        $h = $this->head;
        $t = $this->tail;

        for ($i=0; $i < 100 ; $i++) { $result = rand(1,100); if($result > 50 ) { $h += 1; } else { $t += 1; } }

        if( $bet == "head" ) { $h -= $odd; $t += $odd; }
        if( $bet == "tail" ) { $t -= $odd; $h += $odd; }        
        if( $bet == "head" ) { $result = "HEAD"; if($h < 50) { $result = "TAIL"; } }
        if( $bet == "tail" ) { $result = "TAIL"; if($t < 50) { $result = "HEAD"; } }

        $this->saveResult($bet, $amount, "0001", $result);

        return array("result" => $result, "amount" => $amount);

    }

    private function saveResult($bet = null, $bet_amount = 0, $user_id = null, $result = null) {

        $this->request->data['bet'] = $bet;
        $this->request->data['bet_amount'] = $bet_amount;
        $this->request->data['user_id'] = $user_id;
        $this->request->data['result'] = $result;

        if($this->Result->save($this->request->data)) {
            return true;
        }
        return false;

    }
}
