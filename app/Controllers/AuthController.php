<?php

namespace App\Controllers;

use App\Models\User;

class AuthController extends Controller
{

  public function login($request, $response) {

    $basePath = $request->getUri()->getBasePath();

    $user = User::select('id', 'username', 'password')->where('username', '=', $request->getParam('username'))->first();

    if ($user) {

      if (password_verify($request->getParam('password'), $user->password)) {
        $_SESSION["loggedin"] = $user->id;
        return $this->response->withHeader('Location', $basePath . '/');
      } else {
        $_SESSION["auth_fail"] = "Your Username and Password didn't Match";
        return $this->response->withHeader('Location', $basePath . '/#login');
      }

    } else {
      $_SESSION["auth_fail"] = "This User doesn't exist";
      return $this->response->withHeader('Location', $basePath . '/#login');
    }

  }

  public function register($request, $response) {

    $basePath = $request->getUri()->getBasePath();

    if ($request->getParam('username') == null || $request->getParam('email') == null || $request->getParam('password') == null) {
      $_SESSION["auth_fail"] = "You have to enter a Username, Email and Password";
      return $this->response->withHeader('Location', $basePath . '/#register');
    } elseif ($request->getParam('password') != $request->getParam('password_confirm')) {
      $_SESSION["auth_fail"] = "Your passwords don't match";
      return $this->response->withHeader('Location', $basePath . '/#register');
    } elseif ($request->getParam('skype') == null && $request->getParam('discord') == null) {
      $_SESSION["auth_fail"] = "You have to enter Skype or Discord";
      return $this->response->withHeader('Location', $basePath . '/#register');
    } elseif (User::where('username', '=', $request->getParam('username'))->exists()) {
      $_SESSION["auth_fail"] = "This username already exists";
      return $this->response->withHeader('Location', $basePath . '/#register');
    } else {

      $options = [
        'cost' => 11,
        'salt' => random_bytes(22, MCRYPT_DEV_URANDOM),
      ];

      $password_hash = password_hash($request->getParam('password'), PASSWORD_BCRYPT, $options);

      User::create([
        'username'  => $request->getParam('username'),
        'email'     => $request->getParam('email'),
        'password'  => $password_hash,
        'skype'     => $request->getParam('skype'),
        'discord'   => $request->getParam('discord'),
      ]);

      $user = User::select('id', 'username', 'password')->where('username', '=', $request->getParam('username'))->first();

      if (password_verify($request->getParam('password'), $user->password)) {
        $_SESSION["loggedin"] = $user->id;
        return $this->response->withHeader('Location', $basePath . '/solo');
      }

    }

  }

  public function logout($request, $response) {

    $basePath = $request->getUri()->getBasePath();

    session_unset($_SESSION["loggedin"]);

    return $this->response->withHeader('Location', $basePath . '/');

  }

}
