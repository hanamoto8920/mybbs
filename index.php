<?php
$dataFile = 'bbs.dat';

function h($s)
{
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

if (
  $_SERVER['REQUEST_METHOD'] == 'POST' &&
  isset($_POST['message']) &&
  isset($_POST['user'])
) {

  $message = trim($_POST['message']);
  $user = trim($_POST['user']);


  if ($message !== '') {
    $user = ($user === '') ? '名無し' : $user;

    $message = str_replace("\t", '', $message);
    $user = str_replace("\t", '', $user);

    $postedAt = date('Y/m/d  H:i:s');

    // このデータ↓
    $newData = $message . "\t" . $user . "\t" . $postedAt . "\n";

    $fp = fopen($dataFile, 'a');
    fwrite($fp, $newData);
    fclose($fp);
  }
}

$posts = file($dataFile, FILE_IGNORE_NEW_LINES);

$posts = array_reverse($posts);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>MyBBS</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
<header>
  <h1>MyBBS(簡易掲示板)</h1>
</header>
  

  <h2>投稿一覧(<?= count($posts); ?>件)</h2>
  <ul class="post-lists"
    <?php if (count($posts)) : ?>
      <?php foreach ($posts as $post) : ?>
        <?php list($message, $user, $postedAt) = explode("\t", $post); ?>
        <li>[投稿者] <?php echo h($user); ?>/[投稿日時] <?php echo h($postedAt); ?><br>
          [本文]<br><?php echo h($message); ?>
        </li>
      <?php endforeach; ?>
    <?php else : ?>
      <li>まだ投稿はありません。</li>
    <?php endif; ?>
  </ul>

  <h2>投稿</h2>
  <p class="form-text">こちらから投稿してください。</p>
  <form action="" method="post">
    メッセージ: <input type="text" name="message">
    投稿者名: <input type="text" name="user">
    <input type="submit" value="投稿">
  </form>
  <footer>
      <p>Copyright © MyBBS All Rights Reserved.</p>
  </footer>
</body>

</html>