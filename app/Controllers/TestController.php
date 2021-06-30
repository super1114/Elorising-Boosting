<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Match;

class TestController extends Controller
{

  public function index($request, $response) {

    $user = User::find($_SESSION["loggedin"]);

    $apikey = "RGAPI-bb45ec1c-ee1f-45d4-a84a-7a6a32949732";
    $server = "euw1";
    $summonerName = "4RV1D";

    $url = "https://" . $server . ".api.riotgames.com/lol/summoner/v3/summoners/by-name/" . $summonerName . "?api_key=" . $apikey;

    $result = file_get_contents($url);
    $object = json_decode($result);

    $summonerId = $object->id;
    $accountId = $object->accountId;



    $url = "https://" . $server . ".api.riotgames.com/lol/match/v3/matchlists/by-account/" . $accountId . "?api_key=" . $apikey;

    $result = file_get_contents($url);
    $object = json_decode($result);

    $allMatches = $object->matches;
    $latestMatch = $object->matches[0];

    $latestMatch->gameId;

    $latestGamePlayed = "3650965374";

    $i = 0;
    while (true) {

      $match = $allMatches[$i];

      if ($match->gameId == $latestGamePlayed) {
        break;
      }

      $url = "https://" . $server . ".api.riotgames.com/lol/match/v3/matches/" . $match->gameId . "?api_key=" . $apikey;

      $result = file_get_contents($url);
      $object = json_decode($result);

      for ($x=0; $x < 10; $x++) {

        $participant = $object->participantIdentities[$x];

        if ($participant->player->currentAccountId == $accountId) {
          $participantId = $participant->participantId;
        }
      }

      for ($x=0; $x < 10; $x++) {

        $participant = $object->participants[$x];

        if ($participant->participantId == $participantId) {
          $stats = $participant->stats;
        }
      }

      $kda = $stats->kills . "/" . $stats->deaths . "/" . $stats->assists;

      Match::create([
        'summonerId'    => $summonerId,
        'accountId'     => $accountId,
        'gameId'        => $match->gameId,
        'win'           => $stats->win,
        'kda'           => $kda,
        'minion_kills'  => $stats->totalMinionsKilled,
        'damage_dealt'  => $stats->totalDamageDealt,
        'gold_earned'   => $stats->goldEarned,
        'double_kills'  => $stats->doubleKills,
        'triple_kills'  => $stats->tripleKills,
        'qudra_kills'   => $stats->quadraKills,
        'penta_kills'   => $stats->pentaKills,
      ]);

      $i++;
    }


    /*$url = "https://" . $server . ".api.riotgames.com/lol/match/v3/matches/" . $latestMatch->gameId . "?api_key=" . $apikey;

    $result = file_get_contents($url);
    $object = json_decode($result);

    echo "<pre>";
    //print_r($object->participants);

    for ($i=0; $i < 10; $i++) {

      $participant = $object->participantIdentities[$i];

      if ($participant->player->currentAccountId == $accountId) {
        $participantId = $participant->participantId;
      }
    }

    for ($i=0; $i < 10; $i++) {

      $participant = $object->participants[$i];

      if ($participant->participantId == $participantId) {
        $stats = $participant->stats;
      }
    }

    echo "Kills: " . $stats->kills;
    echo "<br>Deaths: " . $stats->deaths;
    echo "<br>Assits: " . $stats->assists;
    echo "<br>Total Minions Killed: " . $stats->totalMinionsKilled;
    echo "<br>Total Damage Dealt: " . $stats->totalDamageDealt;
    echo "<br>Gold earned: " . $stats->goldEarned;
    echo "<br>Double Kills: " . $stats->doubleKills;
    echo "<br>Triple Kills: " . $stats->tripleKills;
    echo "<br>Quadra Kills: " . $stats->quadraKills;
    echo "<br>Penta Kills: " . $stats->pentaKills;

    $kda = $stats->kills . "/" . $stats->deaths . "/" . $stats->assists;

    Match::create([
      'summonerId'    => $summonerId,
      'accountId'     => $accountId,
      'gameId'        => $latestMatch->gameId,
      'kda'           => $kda,
      'minion_kills'  => $stats->totalMinionsKilled,
      'damage_dealt'  => $stats->totalDamageDealt,
      'gold_earned'   => $stats->goldEarned,
      'double_kills'  => $stats->doubleKills,
      'triple_kills'  => $stats->tripleKills,
      'qudra_kills'   => $stats->quadraKills,
      'penta_kills'   => $stats->pentaKills,
    ]);*/

    /*$apikey = "RGAPI-c674a5e6-db4a-4152-9aae-55d06ade4c9e";
    $server = "euw1";

    $summonerName = "4RV1D";

    $url = "https://" . $server . ".api.riotgames.com/lol/summoner/v3/summoners/by-name/" . $summonerName . "?api_key=" . $apikey;

    $result = file_get_contents($url);
    $object = json_decode($result);

    echo $summonerId = $object->id;
    echo "<br>";
    echo $accountId = $object->accountId;
    echo "<br>";

    $url = "https://" . $server . ".api.riotgames.com/lol/league/v3/positions/by-summoner/" . $summonerId . "?api_key=" . $apikey;

    $result = file_get_contents($url);
    $object = json_decode($result);

    $summonerLeague = $object[0];

    echo $summonerTier = $summonerLeague->tier;
    echo " ";
    echo $summonerRank = $summonerLeague->rank;
    echo "<br> Wins: ";
    echo $summonerWins = $summonerLeague->wins;
    echo "<br> Losses: ";
    echo $summonerLosses = $summonerLeague->losses;
    echo "<br> LP: ";
    echo $summonerLp = $summonerLeague->leaguePoints;*/

    /*echo "<pre>";
    print_r($object);*/

    //$lan = $object[0];

    /*return $this->view->render($response, 'home.twig', array(
      'id' => $lan->id,
      'name' => $lan->name,
      'rules' => $lan->rules,
      'satus' => $lan->status,
      'location' => $lan->location,
      'address' => $lan->address,
      'startDate' => $lan->startDate,
      'endDate' => $lan->endDate
    ));*/

  }

}
