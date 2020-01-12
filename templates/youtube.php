<?php
error_reporting(0);
require __DIR__ . '/../../vendor/autoload.php';
if (isset($_POST['tipe']) && isset($_POST['link'])) {

    $link = $_POST['link'];
    $tipe = $_POST['tipe'];
    if (!filter_var($link, FILTER_VALIDATE_URL)) {
        goto error;
    }

    $yt = new YouTubeDownloader();
    $links = $yt->getDownloadLinks($link);
    if ($tipe == 'mp4') {
        header('Location: ' . $links[array_key_first($links)]['url']);
    }
    $previous = "javascript:history.go(-1)";
    if (isset($_SERVER['HTTP_REFERER'])) {
        $previous = $_SERVER['HTTP_REFERER'];
    }
    error:
    ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<style type="text/css">
    div.body {
        width:98%;
    }
</style>
<br>
<center>
<div class="body">

    <div class="alert alert-danger" role="alert">
    Cannot download format <?= $tipe ?>. <a href="<?= $previous ?>">Back</a>
    </div>
</div>
</center>
<?php
}
