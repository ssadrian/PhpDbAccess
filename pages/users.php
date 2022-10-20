<?php
require_once "utils/sanitizer.php";
require_once "utils/helpers.php";

require_once "controllers/UserController.php";
require_once "models/User.php";

$userController = UserController::getInstance();

$filterGuid = $_POST["filter-guid"] ?? "";
$filterName = $_POST["filter-name"] ?? "";
$filterSurname = $_POST["filter-surname"] ?? "";
$filterUsername = $_POST["filter-username"] ?? "";
$filterEmail = $_POST["filter-email"] ?? "";
$filterPassword = $_POST["filter-password"] ?? "";

$filterUser = getPurifiedUser(
    new User(
        $filterName, $filterSurname, $filterUsername, $filterEmail, $filterPassword, $filterGuid
    )
);

$allUsers = $filteredUsers = $userController->getAll();
if ($filterUser->isInitialized()) {
    $filteredUsers = $userController->getFiltered($filterUser);
}

?>

<form id="filter-form" action="#" method="post"></form>

<table class="table table-hover">
  <thead>
    <tr>
      <th>
        #
      </th>

      <th>
          <?php
          createDropdownTextSelector(
              array_map(function (User $item) {
                  return $item->guid;
              }, $allUsers),
              "Guid",
              $filterGuid
          );
          ?>
      </th>

      <th>
          <?php
          createDropdownTextSelector(
              array_map(function (User $item) {
                  return $item->name;
              }, $allUsers),
              "Name",
              $filterName
          );
          ?>
      </th>

      <th>
          <?php
          createDropdownTextSelector(
              array_map(function (User $item) {
                  return $item->surname;
              }, $allUsers),
              "Surname",
              $filterSurname
          );
          ?>
      </th>

      <th>
          <?php
          createDropdownTextSelector(
              array_map(function (User $item) {
                  return $item->username;
              }, $allUsers),
              "Username",
              $filterUsername
          );
          ?>
      </th>

      <th>
          <?php
          createDropdownTextSelector(
              array_map(function (User $item) {
                  return $item->email;
              }, $allUsers),
              "Email",
              $filterEmail
          );
          ?>
      </th>

      <th>
          <?php
          createDropdownTextSelector(
              array_map(function (User $item) {
                  return $item->password;
              }, $allUsers),
              "Password",
              $filterPassword
          );
          ?>
      </th>

      <th class="text-center">
        <button class="btn btn-outline-primary"
                type="submit" form="filter-form" name="action" value="users">
          Apply
        </button>
      </th>
    </tr>
  </thead>

  <tbody>
      <?php
      foreach ($filteredUsers as $count => $dirtyUser) {
          $item = getPurifiedUser($dirtyUser);

          echo "<tr>";
          echo "<td>$count</td>";
          echo "<td>$item->guid</td>";
          echo "<td>$item->name</td>";
          echo "<td>$item->surname</td>";
          echo "<td>$item->username</td>";
          echo "<td>$item->email</td>";
          echo "<td>$item->password</td>";
          echo "<td class='text-center'>
<form action='#' method='post'>
    <input type='text' name='guid' value='" . $item->guid . "' hidden>

    <button class='btn btn-outline-dark' type='submit' name='action' value='update'>Edit</button>
    <button class='btn btn-outline-danger' type='submit' name='action' value='delete'>Delete</button>
</form>
";
          echo "</tr>";
      }
      ?>
  </tbody>
</table>
