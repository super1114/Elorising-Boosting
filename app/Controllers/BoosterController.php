<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Match;
use App\Models\Api;
use App\Models\Checkout;
use App\Models\CompleteOrder;

class BoosterController extends Controller
{

  public function index($request, $response) {

    $basePath = $request->getUri()->getBasePath();
    $loggedin = $_SESSION["loggedin"];

    $user = User::where('id', '=', $loggedin)->first();

    if ($user->user_type == "booster" || $user->user_type == "admin") {
      return $this->view->render($response, 'booster-panel.twig', array(
        'user' => $user,
      ));
    } else {
      return $this->response->withHeader('Location', $basePath . '/');
    }
  }

  public function getOrders($request, $response) {

    $basePath = $request->getUri()->getBasePath();
    $loggedin = $_SESSION["loggedin"];

    $user = User::where('id', '=', $loggedin)->first();

    if ($user->user_type == "booster" || $user->user_type == "admin") {

      if ($user->paypal != "" && $user->opgg != "") {

        $orders = Order::where('order_taken_by', '=', null)->get();
        $amount = Order::where('order_taken_by', '=', null)->count();

        return $this->view->render($response, 'booster-orders.twig', array(
          'orders' => $orders,
          'amount' => $amount,
          'user' => $user,
        ));

      } else {
        return $this->response->withHeader('Location', $basePath . '/booster-panel');
      }

    } else {
      return $this->response->withHeader('Location', $basePath . '/');
    }
  }

  public function getOrder($request, $response) {

    $basePath = $request->getUri()->getBasePath();
    $loggedin = $_SESSION["loggedin"];

    $booster = User::where('id', '=', $loggedin)->first();

    if ($booster->user_type == "booster" || $booster->user_type == "admin") {

      $order = Order::where('order_taken_by', '=', $loggedin)->first();
      $matches = Match::where('orderId', '=', $order->id)->get();

      $match_wins = Match::where('orderId', '=', $order->id)->where('win', '=', '1')->count();
      $match_losses = Match::where('orderId', '=', $order->id)->where('win', '!=', '1')->count();

      if (!$order) {
        return $this->response->withHeader('Location', $basePath . '/booster-panel/orders');
      }

      $user = User::where('id', '=', $order->ordered_by)->first();

      return $this->view->render($response, 'booster-order.twig', array(
        'order' => $order,
        'user' => $user,
        'booster' => $booster,
        'matches' => $matches,
        'match_wins' => $match_wins,
        'match_losses' => $match_losses,
      ));

    } else {
      return $this->response->withHeader('Location', $basePath . '/');
    }
  }

  public function orderHistory($request, $response) {

    $basePath = $request->getUri()->getBasePath();
    $loggedin = $_SESSION["loggedin"];

    $user = User::where('id', '=', $loggedin)->first();

    if ($user->user_type == "booster" || $user->user_type == "admin") {

      $orders = CompleteOrder::where('boosterId', '=', $loggedin)->get();

      return $this->view->render($response, 'booster-order-history.twig', array(
        'orders' => $orders,
        'user' => $user,
      ));

    } else {
      return $this->response->withHeader('Location', $basePath . '/');
    }
  }

  public function takeOrder($request, $response) {

    $basePath = $request->getUri()->getBasePath();

    $order = Order::where('id', '=', $request->getParam('orderId'))->update([
      'order_taken_by' => $request->getParam('order_taken_by'),
    ]);

    return $this->response->withHeader('Location', $basePath . '/booster-panel/order');

  }

  public function cancelOrder($request, $response) {

    $basePath = $request->getUri()->getBasePath();

    $order = Order::where('id', '=', $request->getParam('orderId'))->update([
      'order_taken_by' => null,
    ]);

    return $this->response->withHeader('Location', $basePath . '/booster-panel/order');

  }

  public function updateOrder($request, $response) {

    $basePath = $request->getUri()->getBasePath();

    $user = User::where('id', '=', $request->getParam('userId'))->first();
    $api = Api::where('name', '=', 'riot')->first();

    $apikey = $api->key;
    $server = $user->lol_server;
    $summonerName = $user->lol_summonerName;
    $latestGamePlayed = $user->lol_latestGameId;


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
        'orderId'       => $request->getParam('orderId'),
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

    $lastMatchPlayed = $allMatches[0];

    User::where('id', '=', $request->getParam('userId'))->update([
      'lol_latestGameId' => $lastMatchPlayed->gameId,
    ]);

    return $this->response->withHeader('Location', $basePath . '/booster-panel/order');

  }

  public function completeOrder($request, $response) {

    $basePath = $request->getUri()->getBasePath();
    $loggedin = $_SESSION["loggedin"];

    $user = User::where('id', '=', $request->getParam('userId'))->first();
    $order = Order::where('id', '=', $request->getParam('orderId'))->first();
    $api = Api::where('name', '=', 'riot')->first();

    $apikey = $api->key;
    $server = $user->lol_server;
    $summonerName = $user->lol_summonerName;
    $latestGamePlayed = $user->lol_latestGameId;

    $gameAmount = substr($order->desiredRank, -1);

    $url = "https://" . $server . ".api.riotgames.com/lol/summoner/v3/summoners/by-name/" . $summonerName . "?api_key=" . $apikey;

    $result = file_get_contents($url);
    $object = json_decode($result);

    $summonerId = $object->id;
    $accountId = $object->accountId;

    $url = "https://" . $server . ".api.riotgames.com/lol/match/v3/matchlists/by-account/" . $accountId . "?api_key=" . $apikey;

    $result = file_get_contents($url);
    $object = json_decode($result);

    $allMatches = $object->matches;

    if ($order->order_type == "solo") {

      $url = "https://" . $server . ".api.riotgames.com/lol/league/v3/positions/by-summoner/" . $summonerId . "?api_key=" . $apikey;

      $result = file_get_contents($url);
      $object = json_decode($result);

      $player = $object[0];

      $current = strtoupper($order->desiredRank);
      $goal = $player->tier . " " . $player->rank;

      if ($current == $goal) {

        $booster = User::where('id', '=', $loggedin)->first();
        $wins = Match::where('orderId', '=', $order->id)->where('win', '=', true)->count();
        $losses = Match::where('orderId', '=', $order->id)->where('win', '=', false)->count();

        $win_loss = $wins . "/" . $losses;
        $payment = $order->price * ($booster->procentage / 100);
        $balance = $booster->balance + $payment;

        User::where('id', '=', $loggedin)->update([
          'balance' => $balance,
        ]);

        User::where('id', '=', $user->id)->update([
          'can_review' => $order->id,
        ]);

        CompleteOrder::create([
          'orderId'     => $order->id,
          'price'       => $order->price,
          'procentage'  => $booster->procentage,
          'order_type'  => $order->order_type,
          'start_rank'  => $order->currentRank,
          'end_rank'    => $order->desiredRank,
          'win_loss'    => $win_loss,
          'boosterId'   => $booster->id,
        ]);

        Order::destroy($order->id);

        return $this->response->withHeader('Location', $basePath . '/booster-panel');

      }

      //return $this->response->withHeader('Location', $basePath . '/booster-panel/order');
    }

    if ($order->order_type == "duo") {

      $i = 0;
      $count = 0;
      while (true) {

        $match = $allMatches[$i];

        if ($match->gameId == $latestGamePlayed) {
          break;
        }

        if ($match->queue != "470" || $match->queue != "440" || $match->queue != "420" || $match->queue != "410" || $match->queue != "400" || $match->queue != "42" || $match->queue != "6") {
          goto end3;
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

        if ($stats->win == "1") {
          $count++;
        }

        if ($count == $gameAmount) {

          $booster = User::where('id', '=', $loggedin)->first();
          $wins = Match::where('orderId', '=', $order->id)->where('win', '=', true)->count();
          $losses = Match::where('orderId', '=', $order->id)->where('win', '=', false)->count();

          $win_loss = $wins . "/" . $losses;
          $payment = $order->price * ($booster->procentage / 100);
          $balance = $booster->balance + $payment;

          User::where('id', '=', $loggedin)->update([
            'balance' => $balance,
          ]);

          User::where('id', '=', $user->id)->update([
            'can_review' => $order->id,
          ]);

          CompleteOrder::create([
            'orderId'     => $order->id,
            'price'       => $order->price,
            'procentage'  => $booster->procentage,
            'order_type'  => $order->order_type,
            'start_rank'  => $order->currentRank,
            'end_rank'    => $order->desiredRank,
            'win_loss'    => $win_loss,
            'boosterId'   => $booster->id,
          ]);

          Order::destroy($order->id);

          return $this->response->withHeader('Location', $basePath . '/booster-panel');

          break;
        }

        end3:
        $i++;
      }

      return $this->response->withHeader('Location', $basePath . '/booster-panel/order');
    }

    if ($order->order_type == "placements") {

      $i = 0;
      $count = 0;
      while (true) {

        $match = $allMatches[$i];

        if ($match->gameId == $latestGamePlayed) {
          break;
        }

        if ($match->queue != "470" || $match->queue != "440" || $match->queue != "420" || $match->queue != "410" || $match->queue != "400" || $match->queue != "42" || $match->queue != "6") {
          goto end;
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

        if ($stats->win == "1") {
          $count++;
        }

        if ($count == $gameAmount) {

          $booster = User::where('id', '=', $loggedin)->first();
          $wins = Match::where('orderId', '=', $order->id)->where('win', '=', true)->count();
          $losses = Match::where('orderId', '=', $order->id)->where('win', '=', false)->count();

          $win_loss = $wins . "/" . $losses;
          $payment = $order->price * ($booster->procentage / 100);
          $balance = $booster->balance + $payment;

          User::where('id', '=', $loggedin)->update([
            'balance' => $balance,
          ]);

          User::where('id', '=', $user->id)->update([
            'can_review' => $order->id,
          ]);

          CompleteOrder::create([
            'orderId'     => $order->id,
            'price'       => $order->price,
            'procentage'  => $booster->procentage,
            'order_type'  => $order->order_type,
            'start_rank'  => $order->currentRank,
            'end_rank'    => $order->desiredRank,
            'win_loss'    => $win_loss,
            'boosterId'   => $booster->id,
          ]);

          Order::destroy($order->id);

          return $this->response->withHeader('Location', $basePath . '/booster-panel');

          break;
        }

        end:
        $i++;
      }

      return $this->response->withHeader('Location', $basePath . '/booster-panel/order');
    }

    if ($order->order_type == "wins") {

      $i = 0;
      $count = 0;
      while (true) {

        $match = $allMatches[$i];

        if ($match->gameId == $latestGamePlayed) {
          break;
        }

        if ($match->queue != "430") {
          goto end2;
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

        if ($stats->win == "1") {
          $count++;
        }

        if ($count == $gameAmount) {

          $booster = User::where('id', '=', $loggedin)->first();
          $wins = Match::where('orderId', '=', $order->id)->where('win', '=', true)->count();
          $losses = Match::where('orderId', '=', $order->id)->where('win', '=', false)->count();

          $win_loss = $wins . "/" . $losses;
          $payment = $order->price * ($booster->procentage / 100);
          $balance = $booster->balance + $payment;

          User::where('id', '=', $loggedin)->update([
            'balance' => $balance,
          ]);

          User::where('id', '=', $user->id)->update([
            'can_review' => $order->id,
          ]);

          CompleteOrder::create([
            'orderId'     => $order->id,
            'price'       => $order->price,
            'procentage'  => $booster->procentage,
            'order_type'  => $order->order_type,
            'start_rank'  => $order->currentRank,
            'end_rank'    => $order->desiredRank,
            'win_loss'    => $win_loss,
            'boosterId'   => $booster->id,
          ]);

          Order::destroy($order->id);

          return $this->response->withHeader('Location', $basePath . '/booster-panel/order');

          break;
        }

        end2:
        $i++;
      }

      return $this->response->withHeader('Location', $basePath . '/booster-panel/order');
    }

    return $this->response->withHeader('Location', $basePath . '/booster-panel/order');

  }


  public function updateBooster($request, $response) {

    $basePath = $request->getUri()->getBasePath();
    $loggedin = $_SESSION["loggedin"];

    User::where('id', '=', $loggedin)->update([
      'paypal' => $request->getParam('paypal'),
      'opgg' => $request->getParam('opgg'),
    ]);

    return $this->response->withHeader('Location', $basePath . '/booster-panel');
  }

  public function checkout($request, $response) {

    $basePath = $request->getUri()->getBasePath();
    $loggedin = $_SESSION["loggedin"];

    if ($request->getParam('balance') < 200) {

      return $this->response->withHeader('Location', $basePath . '/booster-panel');

    } else {

      Checkout::create([
        'userId'  => $request->getParam('userId'),
        'payment' => $request->getParam('balance'),
        'paypal'  => $request->getParam('paypal'),
      ]);

      $user = User::find($request->getParam('userId'));

      $balance = $request->getParam('userId') + $user->waiting_balance;

      User::where('id', '=', $loggedin)->update([
        'waiting_balance'   => $balance,
        'balance'           => 0,
      ]);

      return $this->response->withHeader('Location', $basePath . '/booster-panel');
    }

  }

}
