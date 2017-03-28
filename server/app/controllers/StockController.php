<?php
namespace Sam\Server\Controllers;
use Sam\Server\Libraries\StockLibrary;
use Sam\Server\Models\OwnedStock;
/**
 * Created by PhpStorm.
 * User: www-data
 * Date: 24.03.17
 * Time: 09:47
 */
class StockController extends ControllerBase
{
    public function getAction($param, $symbol = false) {
        if($symbol && $param == "symbol") {
            return json_encode(StockLibrary::getStocksBySymbols(explode(":", $symbol)));
        } else if($symbol && $param == "history") {
            return json_encode(StockLibrary::getStockHistoryBySymbol($symbol));
        } else {
            return json_encode(StockLibrary::getStockByCompanyName($param));
        }
    }

    public function postAction() {
        $direction = $this->request->getPost("direction");
        $shares = $this->request->getPost("shares");
        /*
         * $direction = 0 => buy
         * $direction = 1 => sell
         */
        if($direction == 0) {

            $symbol = $this->request->getPost("symbol");
            $depotId = $this->request->getPost("depotId");

            //pps = price per share
            $transaction = StockLibrary::buy($symbol, $shares, $depotId, $this->session->get("auth"));
            return json_encode($transaction);

        } else if($direction == 1) {
            $ownedStockId = $this->request->getPost("ownedStockId");
            $ownedStock = OwnedStock::findFirst(array("id = :id:", "bind" => array("id" => $ownedStockId)));

            $transaction = StockLibrary::sell($ownedStock, $shares, $this->session->get("auth"));
            return json_encode($transaction);

        } else {
            return json_encode(array("error"=>"Wrong direction!"));
        }
    }
}