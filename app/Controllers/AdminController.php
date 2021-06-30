<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Match;
use App\Models\CompleteOrder;
use App\Models\Referral;
use App\Models\Api;
use App\Models\Checkout;

class AdminController extends Controller
{

  public function index($request, $response) {

    $basePath = $request->getUri()->getBasePath();
    $loggedin = $_SESSION["loggedin"];

    $user = User::where('id', '=', $loggedin)->first();

    if ($user->user_type != "admin") {
      return $this->response->withHeader('Location', $basePath . '/');
    }

    $api = Api::where('name', '=', 'riot')->first();

    return $this->view->render($response, 'admin-panel.twig', array(
      'active_orders' => $active_orders,
      'waiting_orders' => $waiting_orders,
      'completed_orders' => $completed_orders,
      'booster_accounts' => $booster_accounts,
      'user_accounts' => $user_accounts,
      'total_matches' => $total_matches,
      'api' => $api,
    ));
  }

  public function orders($request, $response) {

    $basePath = $request->getUri()->getBasePath();
    $loggedin = $_SESSION["loggedin"];

    $user = User::where('id', '=', $loggedin)->first();

    if ($user->user_type != "admin") {
      return $this->response->withHeader('Location', $basePath . '/');
    }

    $order_state = $request->getAttribute('order_state');

    if ($order_state == "unpaid") {

      $orders = Order::where('order_done', '=', true)->where('order_paid', '=', false)->get();

      return $this->view->render($response, 'admin-orders.twig', array(
        'order_state' => $order_state,
        'orders' => $orders,
      ));
    }

    if ($order_state == "paid") {

      $orders = CompleteOrder::get();

      return $this->view->render($response, 'admin-orders.twig', array(
        'order_state' => $order_state,
        'orders' => $orders,
      ));
    }

    if ($order_state == "active") {

      $orders = Order::where('order_taken_by', '!=', null)->where('order_done', '=', false)->get();

      return $this->view->render($response, 'admin-orders.twig', array(
        'order_state' => $order_state,
        'orders' => $orders,
      ));
    }

    if ($order_state == "waiting") {

      $orders = Order::where('order_taken_by', '=', null)->get();

      return $this->view->render($response, 'admin-orders.twig', array(
        'order_state' => $order_state,
        'orders' => $orders,
      ));
    }

    return $this->response->withHeader('Location', $basePath . '/admin-panel');

  }

  public function boosters($request, $response) {

    $basePath = $request->getUri()->getBasePath();
    $loggedin = $_SESSION["loggedin"];

    $user = User::where('id', '=', $loggedin)->first();

    if ($user->user_type != "admin") {
      return $this->response->withHeader('Location', $basePath . '/');
    }

    $users = User::where('user_type', '=', 'booster')->get();

    return $this->view->render($response, 'admin-boosters.twig', array(
      'users' => $users,
    ));

  }

  public function referrals($request, $response) {

    $basePath = $request->getUri()->getBasePath();
    $loggedin = $_SESSION["loggedin"];

    $user = User::where('id', '=', $loggedin)->first();

    if ($user->user_type != "admin") {
      return $this->response->withHeader('Location', $basePath . '/');
    }

    $referrals = Referral::get();

    return $this->view->render($response, 'admin-referrals.twig', array(
      'referrals' => $referrals,
    ));

  }

  public function checkouts($request, $response) {

    $basePath = $request->getUri()->getBasePath();
    $loggedin = $_SESSION["loggedin"];

    $user = User::where('id', '=', $loggedin)->first();

    if ($user->user_type != "admin") {
      return $this->response->withHeader('Location', $basePath . '/');
    }

    $checkouts = Checkout::where('checkout_done', '=', false)->get();
    $paidCheckouts = Checkout::where('checkout_done', '=', true)->get();

    return $this->view->render($response, 'admin-checkouts.twig', array(
      'checkouts' => $checkouts,
      'paidCheckouts' => $paidCheckouts,
    ));

  }

  public function addReferral($request, $response) {

    $basePath = $request->getUri()->getBasePath();

    $referral = Referral::create([
      'referral_name' => $request->getParam('referral_name'),
      'referral_code' => $request->getParam('referral_code'),
      'referral_discount' => $request->getParam('referral_discount'),
    ]);

    if ($referral) {
      return $this->response->withHeader('Location', $basePath . '/admin-panel');
    } else {
      echo "Something went Wrong!";
    }

  }

  public function addBooster($request, $response) {

    $basePath = $request->getUri()->getBasePath();

    if (User::where('username', '=', $request->getParam('booster_username'))->exists()) {

      User::where('username', '=', $request->getParam('booster_username'))->update([
        'procentage' => $request->getParam('booster_procentage'),
        'user_type' => 'booster',
      ]);

      return $this->response->withHeader('Location', $basePath . '/admin-panel');

    } else {

      echo "This user doesn't exist <a href='" . $basePath . '/admin-panel' . "'>go back to admin panel</a>";

    }

  }

  public function updateApi($request, $response) {

    $basePath = $request->getUri()->getBasePath();

    Api::where('name', '=', 'riot')->update([
      'key' => $request->getParam('apikey'),
    ]);

    return $this->response->withHeader('Location', $basePath . '/admin-panel');

  }

  public function markAsPaid($request, $response) {

    $basePath = $request->getUri()->getBasePath();

    Checkout::where('id', '=', $request->getParam('id'))->update([
      'checkout_done' => true,
    ]);

    $user = User::find($request->getParam('boosterId'));

    $balance = $user->payed_balance + $request->getParam('payment');

    User::where('id', '=', $request->getParam('boosterId'))->update([
      'waiting_balance' => 0,
      'payed_balance' => $balance,
    ]);

    return $this->response->withHeader('Location', $basePath . '/admin-panel/checkouts');

  }

}
