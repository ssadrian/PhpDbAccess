<?php
session_destroy();
?>

<form action="#" method="post">
  <button id="btn-submit" type="submit" name="action" value="login" hidden></button>
</form>

<script type="application/javascript">
    document.getElementById("btn-submit").click();
</script>
