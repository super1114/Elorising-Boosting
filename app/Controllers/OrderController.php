<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Referral;
use App\Models\Api;

class OrderController extends Controller
{

  public function order($request, $response) {

    $basePath = $request->getUri()->getBasePath();

    if ($request->getParam('specific_spellposition')) {
      $spellposition = true;
    } else {
      $spellposition = false;
    }

    if ($request->getParam('favorite_champs')) {
      $favorite_champs = true;
    } else {
      $favorite_champs = false;
    }

    if ($request->getParam('priority_completion')) {
      $priority_completion = true;
    } else {
      $priority_completion = false;
    }

    if ($request->getParam('coching')) {
      $coching = true;
    } else {
      $coching = false;
    }

    if ($request->getParam('offlinemode')) {
      $offlinemode = true;
    } else {
      $offlinemode = false;
    }

    if ($request->getParam('account_warranty')) {
      $account_warranty = true;
    } else {
      $account_warranty = false;
    }

    $array_priorityChampions_1 = array(
      $request->getParam('priority_1champion_1'),
      $request->getParam('priority_1champion_2'),
      $request->getParam('priority_1champion_3'),
      $request->getParam('priority_1champion_4'),
      $request->getParam('priority_1champion_5'),
    );

    $priorityChampions_1 = implode("/", $array_priorityChampions_1);

    $array_priorityChampions_2 = array(
      $request->getParam('priority_2champion_1'),
      $request->getParam('priority_2champion_2'),
      $request->getParam('priority_2champion_3'),
      $request->getParam('priority_2champion_4'),
      $request->getParam('priority_2champion_5'),
    );

    $priorityChampions_2 = implode("/", $array_priorityChampions_2);

    $array_priorityChampions_3 = array(
      $request->getParam('priority_3champion_1'),
      $request->getParam('priority_3champion_2'),
      $request->getParam('priority_3champion_3'),
      $request->getParam('priority_3champion_4'),
      $request->getParam('priority_3champion_5'),
    );

    $priorityChampions_3 = implode("/", $array_priorityChampions_3);

    $rankArray = array(
      '0' => "Bronze V",
      '1' => "Bronze IV",
      '2' => "Bronze III",
      '3' => "Bronze II",
      '4' => "Bronze I",
      '5' => "Silver V",
      '6' => "Silver IV",
      '7' => "Silver III",
      '8' => "Silver II",
      '9' => "Silver I",
      '10' => "Gold V",
      '11' => "Gold IV",
      '12' => "Gold III",
      '13' => "Gold II",
      '14' => "Gold I",
      '15' => "Platinum V",
      '16' => "Platinum IV",
      '17' => "Platinum III",
      '18' => "Platinum II",
      '19' => "Platinum I",
      '20' => "Diamond V",
      '21' => "Diamond IV",
      '22' => "Diamond III",
      '23' => "Diamond II",
      '24' => "Diamond I",
      '25' => "Master",
    );

    if ($request->getParam('order_type') == "solo") {
      $desired = $rankArray[$request->getParam('desiredRank')];
      $current = $rankArray[$request->getParam('currentRank')];
    }

    if ($request->getParam('order_type') == "duo") {
      $desired = $request->getparam('game_type') . " x " . $request->getparam('game_amount');
      $current = $rankArray[$request->getParam('currentRank')];
    }

    if ($request->getParam('order_type') == "placements") {
      $desired = "Placements x " . $request->getparam('game_amount');
      $current = $request->getParam('currentRank');
    }

    if ($request->getParam('order_type') == "wins") {
      $desired = "Normal Wins x " . $request->getparam('game_amount');
      $current = "";
    }

    // Create order in database
    $order = Order::create([
      'price'                   => $request->getParam('price'),
      'currentRank'             => $current,
      'desiredRank'             => $desired,
      'specific_spellposition'  => $spellposition,
      'flash_posistion'         => $request->getParam('flash'),
      'favorite_champs'         => $favorite_champs,
      'priority_lane_1'         => $request->getParam('lane_priority_1'),
      'priority_1_champions'    => $priorityChampions_1,
      'priority_lane_2'         => $request->getParam('lane_priority_2'),
      'priority_2_champions'    => $priorityChampions_2,
      'priority_lane_3'         => $request->getParam('lane_priority_3'),
      'priority_3_champions'    => $priorityChampions_3,
      'priority_completion'     => $priority_completion,
      'coching'                 => $coching,
      'offlinemode'             => $offlinemode,
      'account_warranty'        => $account_warranty,
      'order_type'              => $request->getParam('order_type'),
      'ordered_by'              => $request->getParam('ordered_by'),
      'order_done'              => false,
    ]);

    if ($order) {
      return $this->response->withHeader('Location', $basePath . '/user/order');
    } else {
      echo "There was a mistake with your order, plz contact support.";
    }

  }

  public function updateOrder($request, $response) {

    $basePath = $request->getUri()->getBasePath();

    $user = User::find($_SESSION["loggedin"]);
    $api = Api::where('name', '=', 'riot')->first();



    if ($request->getParam('summonerName') != $user->lol_summonerName || $request->getParam('server') != $user->lol_server) {

      echo "123";

      $apikey = $api->key;

      $url = "https://" . $request->getParam('server') . ".api.riotgames.com/lol/summoner/v3/summoners/by-name/" . $request->getParam('summonerName') . "?api_key=" . $apikey;

      $result = file_get_contents($url);
      $object = json_decode($result);

      $summonerId = $object->id;
      $accountId = $object->accountId;

      $url = "https://" . $request->getParam('server') . ".api.riotgames.com/lol/match/v3/matchlists/by-account/" . $accountId . "?api_key=" . $apikey;

      $result = file_get_contents($url);
      $object = json_decode($result);

      $allMatches = $object->matches;
      $latestMatch = $object->matches[0];

      User::where('id', '=', $_SESSION["loggedin"])->update([
        'lol_summonerId' => $summonerId,
        'lol_accountId' => $accountId,
        'lol_latestGameId' => $latestMatch->gameId,
      ]);

    }

    User::where('id', '=', $_SESSION["loggedin"])->update([
      'lol_summonerName' => $request->getParam('summonerName'),
      'lol_username' => $request->getParam('username'),
      'lol_password' => $request->getParam('password'),
      'lol_server' => $request->getParam('server'),
    ]);

    return $this->response->withHeader('Location', $basePath . '/user/order');

  }

  public function referralCheck($request, $response) {

    if (Referral::where('referral_code', '=', $request->getParam('code'))->exists()) {
      $referral = Referral::where('referral_code', '=', $request->getParam('code'))->first();
      echo $referral->referral_discount;
    } else {
      echo "<p>The referral code could not be found.</p>";
    }

  }

}
