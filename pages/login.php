<?php

require_once "models/User.php";
require_once "controllers/UserController.php";

$userController = UserController::getInstance();

$userOrEmail = $_POST["username-email"] ?? "";
$pwd = $_POST["password"] ?? "";

if (empty($userOrEmail) !== true && empty($pwd) !== true) {
    $user = new User(null, null, null, null, null, null);
    $user->username = $userOrEmail;
    $user->email = $userOrEmail;
    $user->password = $pwd;

    $possibleUsers = $userController->getFiltered($user);

    foreach ($possibleUsers as $possibleUser) {
        $isUsernameCorrect = $possibleUser->username === $user->username;
        $isEmailCorrect = $possibleUser->email === $user->email;
        $isPasswordCorrect = password_verify($user->password, $possibleUser->password);

        if (($isUsernameCorrect || $isEmailCorrect) && $isPasswordCorrect) {
            session_start();
            $_SESSION['isLogged'] = true;

            if (isUserAdmin($possibleUser)) {
              $_SESSION["isAdmin"] = true;
            }

            break;
        }
    }
}

?>

<form action="#" method="post">
  <div class="mb-3">
    <label for="input-username-email" class="col-sm-2 col-form-label">Username/Email</label>

    <div class="col-sm-10">
      <input id="input-username-email" name="username-email" class="form-control">
    </div>
  </div>

  <div class="mb-3">
    <label for="input-password" class="col-sm-2 col-form-label">Password</label>

    <div class="col-sm-10">
      <input id="input-password" type="password" name="password" class="form-control">
    </div>
  </div>

  <button type="submit" name="action" value="login" class="btn btn-outline-dark">
    Login!
  </button>

  <button type="reset" class="btn btn-outline-danger">
    Reset
  </button>
</form>
