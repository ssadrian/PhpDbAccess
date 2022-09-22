<?php

require_once "controllers/ItemController.php";

$guid = $_POST["guid"] ?? "";
$isSuccessful = tryDelete($guid);
?>

<form action="#" method="post" hidden>
  <label>
    <input type="text" name="action" value="read">
    <input type="text" name="result" value="<?php echo $isSuccessful ?>">
  </label>

  <button type="submit" id="submit-btn"></button>
</form>

<script>
  document.getElementById("submit-btn").click();
</script>
