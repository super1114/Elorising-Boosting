<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Match;
use App\Models\Api;
use App\Models\Review;

class HomeController extends Controller
{

  public function index($request, $response) {

    if (isset($_SESSION["loggedin"])) {
      $loggedin = $_SESSION["loggedin"];

      $user = User::find($loggedin);

    } else {
      $loggedin = false;
      $user = false;
    }

    if (isset($_SESSION["auth_fail"])) {
      $auth_fail = $_SESSION["auth_fail"];
      session_unset($_SESSION["auth_fail"]);
    } else {
      $auth_fail = "";
    }

    return $this->view->render($response, 'home.twig', array(
      'auth_fail' => $auth_fail,
      'loggedin' => $loggedin,
      'user' => $user,
    ));

  }

  public function solo($request, $response) {

    if (isset($_SESSION["loggedin"])) {
      $loggedin = $_SESSION["loggedin"];

      $user = User::find($loggedin);
      $activeOrder = Order::where('ordered_by', '=', $loggedin)->exists();

    } else {
      $loggedin = false;
      $user = false;
      $activeOrder = false;
    }

    if (isset($_SESSION["auth_fail"])) {
      $auth_fail = $_SESSION["auth_fail"];
      session_unset($_SESSION["auth_fail"]);
    } else {
      $auth_fail = "";
    }

    return $this->view->render($response, 'solo.twig', array(
      'auth_fail' => $auth_fail,
      'loggedin' => $loggedin,
      'user' => $user,
      'activeOrder' => $activeOrder,
    ));

  }

  public function duo($request, $response) {

    if (isset($_SESSION["loggedin"])) {
      $loggedin = $_SESSION["loggedin"];

      $user = User::find($loggedin);
      $activeOrder = Order::where('ordered_by', '=', $loggedin)->exists();

    } else {
      $loggedin = false;
      $user = false;
      $activeOrder = false;
    }

    if (isset($_SESSION["auth_fail"])) {
      $auth_fail = $_SESSION["auth_fail"];
      session_unset($_SESSION["auth_fail"]);
    } else {
      $auth_fail = "";
    }

    return $this->view->render($response, 'duo.twig', array(
      'auth_fail' => $auth_fail,
      'loggedin' => $loggedin,
      'user' => $user,
      'activeOrder' => $activeOrder,
    ));

  }

  public function placements($request, $response) {

    if (isset($_SESSION["loggedin"])) {
      $loggedin = $_SESSION["loggedin"];

      $user = User::find($loggedin);
      $activeOrder = Order::where('ordered_by', '=', $loggedin)->exists();

    } else {
      $loggedin = false;
      $user = false;
      $activeOrder = false;
    }

    if (isset($_SESSION["auth_fail"])) {
      $auth_fail = $_SESSION["auth_fail"];
      session_unset($_SESSION["auth_fail"]);
    } else {
      $auth_fail = "";
    }

    return $this->view->render($response, 'placements.twig', array(
      'auth_fail' => $auth_fail,
      'loggedin' => $loggedin,
      'user' => $user,
      'activeOrder' => $activeOrder,
    ));

  }

  public function wins($request, $response) {

    if (isset($_SESSION["loggedin"])) {
      $loggedin = $_SESSION["loggedin"];

      $user = User::find($loggedin);
      $activeOrder = Order::where('ordered_by', '=', $loggedin)->exists();

    } else {
      $loggedin = false;
      $user = false;
      $activeOrder = false;
    }

    if (isset($_SESSION["auth_fail"])) {
      $auth_fail = $_SESSION["auth_fail"];
      session_unset($_SESSION["auth_fail"]);
    } else {
      $auth_fail = "";
    }

    return $this->view->render($response, 'wins.twig', array(
      'auth_fail' => $auth_fail,
      'loggedin' => $loggedin,
      'user' => $user,
      'activeOrder' => $activeOrder,
    ));

  }

  /*public function deals($request, $response) {

    if (isset($_SESSION["loggedin"])) {
      $loggedin = $_SESSION["loggedin"];

      $user = User::find($loggedin);

    } else {
      $loggedin = false;
      $user = false;
    }

    if (isset($_SESSION["auth_fail"])) {
      $auth_fail = $_SESSION["auth_fail"];
      session_unset($_SESSION["auth_fail"]);
    } else {
      $auth_fail = "";
    }

    return $this->view->render($response, 'deals.twig', array(
      'auth_fail' => $auth_fail,
      'loggedin' => $loggedin,
      'user' => $user,
    ));

  }*/

  public function order($request, $response) {

    $basePath = $request->getUri()->getBasePath();
    $api = Api::where('name', '=', 'riot')->first();

    $apikey = $api->key;
    $api_fail = "";

    if (isset($_SESSION["loggedin"])) {
      $loggedin = $_SESSION["loggedin"];

      $user = User::find($loggedin);
      $order = Order::where('ordered_by', '=', $user->id)->first();
      $matches = Match::where('orderId', '=', $order->id)->get();

      $match_wins = Match::where('orderId', '=', $order->id)->where('win', '=', '1')->count();
      $match_losses = Match::where('orderId', '=', $order->id)->where('win', '!=', '1')->count();

      if (!$order) {
        return $this->response->withHeader('Location', $basePath . '/');
      }

    } else {
      $loggedin = false;
      $user = false;
      $order = false;
    }

    if (isset($_SESSION["auth_fail"])) {
      $auth_fail = $_SESSION["auth_fail"];
      session_unset($_SESSION["auth_fail"]);
    } else {
      $auth_fail = "";
    }

    $user = User::find($_SESSION["loggedin"]);

    if ($user->lol_summonerName != null) {

      $url = "https://" . $user->lol_server . ".api.riotgames.com/lol/summoner/v3/summoners/by-name/" . $user->lol_summonerName . "?api_key=" . $apikey;
      if ($result = @file_get_contents($url)) {

        $object = json_decode($result);
        $accountInfo = $object;

        $url = "https://" . $user->lol_server . ".api.riotgames.com/lol/league/v3/positions/by-summoner/" . $accountInfo->id . "?api_key=" . $apikey;
        if ($result = @file_get_contents($url)) {

          $object = json_decode($result);

          if (empty($object)) {
            $api_fail = "You are not ranked, we couldn't find your league status.";
          } else {

            $summonerLeague = $object[0];

            return $this->view->render($response, 'user-order.twig', array(
              'api_fail' => $api_fail,
              'auth_fail' => $auth_fail,
              'loggedin' => $loggedin,
              'user' => $user,
              'order' => $order,
              'matches' => $matches,
              'match_wins' => $match_wins,
              'match_losses' => $match_losses,
              'tier' => $summonerLeague->tier,
              'rank' => $summonerLeague->rank,
              'wins' => $summonerLeague->wins,
              'losses' => $summonerLeague->losses,
              'lp' => $summonerLeague->leaguePoints,
            ));
          }
        }

      } else {
        $api_fail = "Your Summoner Name or Server is wrong, We couldn't find your account.";
      }

    }

    return $this->view->render($response, 'user-order.twig', array(
      'api_fail' => $api_fail,
      'auth_fail' => $auth_fail,
      'loggedin' => $loggedin,
      'user' => $user,
      'order' => $order,
      'matches' => $matches,
      'match_wins' => $match_wins,
      'match_losses' => $match_losses,
    ));

  }

  public function reviews($request, $response) {

    if (isset($_SESSION["loggedin"])) {
      $loggedin = $_SESSION["loggedin"];

      $user = User::find($loggedin);

    } else {
      $loggedin = false;
      $user = false;
    }

    if (isset($_SESSION["auth_fail"])) {
      $auth_fail = $_SESSION["auth_fail"];
      session_unset($_SESSION["auth_fail"]);
    } else {
      $auth_fail = "";
    }

    $reviews = Review::orderBy('id', 'DESC')->get();

    return $this->view->render($response, 'reviews.twig', array(
      'auth_fail' => $auth_fail,
      'loggedin' => $loggedin,
      'user' => $user,
      'reviews' => $reviews,
    ));

  }

  public function contact($request, $response) {

    if (isset($_SESSION["loggedin"])) {
      $loggedin = $_SESSION["loggedin"];

      $user = User::find($loggedin);

    } else {
      $loggedin = false;
      $user = false;
    }

    if (isset($_SESSION["auth_fail"])) {
      $auth_fail = $_SESSION["auth_fail"];
      session_unset($_SESSION["auth_fail"]);
    } else {
      $auth_fail = "";
    }

    $reviews = Review::orderBy('id', 'DESC')->get();

    return $this->view->render($response, 'contact.twig', array(
      'auth_fail' => $auth_fail,
      'loggedin' => $loggedin,
      'user' => $user,
      'reviews' => $reviews,
    ));

  }

  public function addReview($request, $response) {

    $basePath = $request->getUri()->getBasePath();

    Review::create([
      'orderId' => $request->getParam('orderId'),
      'userId' => $request->getParam('userId'),
      'review_text' => $request->getParam('review_text'),
      'username' => $request->getParam('username'),
      'stars' => $request->getParam('stars'),
    ]);

    User::where('id', '=', $request->getParam('userId'))->update([
      'can_review' => null,
    ]);

    return $this->response->withHeader('Location', $basePath . '/reviews');

  }

  public function mailContact($request, $response) {

    $basePath = $request->getUri()->getBasePath();

    $subject = $request->getParam('category');
    $msg = $request->getParam('message') . "\n \n From: " . $request->getParam('email');

    $mail = mail("support@elorising.com", $subject, $msg);

    return $this->response->withHeader('Location', $basePath . '/contact');

  }

  public function forgotPassword($request, $response) {

    $basePath = $request->getUri()->getBasePath();

    if (User::where('email', '=', $request->getParam('mail'))->exists()) {

      echo "hello";

      $user = User::where('email', '=', $request->getParam('mail'))->first();

      $subject = "Elorising: Password Reset";
      $msg = "Click the link below and follow instructions to reset your password. \n \n
      http://elorising.com/changePassword?id=" . $user->id;

      $mail = mail($request->getParam('mail'), $subject, $msg);

      return $this->response->withHeader('Location', $basePath . '/');

    } else {
      return $this->response->withHeader('Location', $basePath . '/');
    }

  }

  public function changePassword($request, $response) {

    $basePath = $request->getUri()->getBasePath();

    if ($request->getParam('id') != null) {
      $user = User::where('id', '=', $request->getParam('id'))->first();

      return $this->view->render($response, 'change.twig', array(
        'user' => $user,
      ));
    } else {
      return $this->response->withHeader('Location', $basePath . '/');
    }

  }

  public function resetPassword($request, $response) {

    $basePath = $request->getUri()->getBasePath();

    $options = [
      'cost' => 11,
      'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
    ];

    $password_hash = password_hash($request->getParam('password'), PASSWORD_BCRYPT, $options);

    User::where('id', '=', $request->getParam('userId'))->update([
      'password' => $password_hash,
    ]);

    return $this->response->withHeader('Location', $basePath . '/#login');

  }

}
