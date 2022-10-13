<?php

require_once "controllers/UserController.php";
require_once "models/User.php";

$userController = UserController::getInstance();

$name = $_POST["name"] ?? "";
$surname = $_POST["surname"] ?? "";
$username = $_POST["username"] ?? "";
$email = $_POST["email"] ?? "";
$password = $_POST["password"] ?? "";

$user = new User($name, $surname, $username, $email, $password, null);

if ($user->isInitialized()) {
    $isSuccessful = $userController->tryCreate(getCompleteUser($user));
}

?>

<form action="#" method="post">
  <div class="mb-3">
    <label for="input-name" class="col-sm-2 col-form-label">Name</label>

    <div class="col-sm-10">
      <input id="input-name" name="name" class="form-control">
    </div>
  </div>

  <div class="mb-3">
    <label for="input-surname" class="col-sm-2 col-form-label">Surname</label>

    <div class="col-sm-10">
      <input id="input-surname" name="surname" class="form-control">
    </div>
  </div>

  <div class="mb-3">
    <label for="input-username" class="col-sm-2 col-form-label">Username</label>

    <div class="col-sm-10">
      <input id="input-username" name="username" class="form-control">
    </div>
  </div>

  <div class="mb-3">
    <label for="input-email" class="col-sm-2 col-form-label">Email</label>

    <div class="col-sm-10">
      <input id="input-email" name="email" type="email" class="form-control">
    </div>
  </div>

  <div class="mb-3">
    <label for="input-password" class="col-sm-2 col-form-label">Password</label>

    <div class="col-sm-10">
      <input id="input-password" name="password" type="password" class="form-control">
    </div>
  </div>

  <button type="submit" name="action" value="signup" class="btn btn-outline-dark">
    SignUp!
  </button>

  <button type="reset" class="btn btn-outline-danger">
    Reset
  </button>
</form>
