<?php

session_start();

include '../PHP/connect.php';
include '../PHP/GlobalVar.php';
include '../PHP/parsedown.php';

mysqli_set_charset($conn, 'utf8');

$Parsedown = new Parsedown();

if (!empty($_GET['id'])) {
  if (preg_match('/[a-zA-Z\-]*/',$id)) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $id = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');
    $idTitle = str_replace('-',' ',$id);
  }
}

$sql = "SELECT * FROM `articles` WHERE title='$idTitle' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $title = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
    $lang = htmlspecialchars($row['lang'], ENT_QUOTES, 'UTF-8');
    $text = $row['text'];
  }
} else {
  http_response_code(404);
  $title = "404: %{title}";
  $lang = "%{lang}";
  $text = "%{text}";
}

?>

<!DOCTYPE html>
<html>
<head>

<?php echo $GlobalHeadDistro; ?>

<script>
$( document ).ready(function() {
  $("pre code").addClass("prism").prepend('\n').append('\n');
  $("pre").addClass("prism");
});
</script>

</head>

<body>

<div class="bg header">
  <div class="container">
    <div class="inline menuNav">
      <div class="grid-1 logo-grid">
        <a href="http://gefion-programming.com"><img src="../logo.png" class="logo img-responsive-max"></a>
      </div>
      <div class="grid-11 nav-grid">
        <ul>
          <?php echo $globalNav; ?>
        </ul>
      </div>
    </div>
    <div class="grid-12 mt center">
      <h1 class="headerTitle"><?php echo $title; ?></h1>
      <h6 class="headerLanguage"><?php echo $lang; ?></h6>
    </div>
  </div>
</div>

<div class="container">
  <div class="grid-12" id="markdown-render-object">
<?php
echo $Parsedown
->setBreaksEnabled(true)
->setMarkupEscaped(true)
->text($text);
?>
  </div>
</div>

<div class="container">
  <div class="grid-6 offset-3 bt center muted pt mb">
    <p>
      Built using <a href="http://resources.ludvig.xyz/motus/">motus</a>, an open-source framework by <a href="http://ludvig.xyz">Ludvig Alexander Brüchmann</a>.
      <br>
      Copyright &copy; Gefion Programming, <?php echo date("Y");?>
    </p>
  </div>
</div>

</body>
</html>
