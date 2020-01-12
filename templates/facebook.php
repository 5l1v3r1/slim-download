<?php
function getBetween($content, $start, $end)
{
    $r = explode($start, $content);
    if (isset($r[1])) {
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return '';
}
if (isset($_POST["link"])) {
    $link = $_POST['link'];
    $link = trim($link);
    if (!filter_var($link, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) { ?>
      <div class="alert alert-danger" role="alert">
        Not valid link <?php print htmlentities($link); ?>
      </div>
<?php }
    $ch = curl_init($link);
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt(
        $ch,
        CURLOPT_USERAGENT,
        "Mozilla/5.0 (Windows; U;   Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7"
    );
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    $html_encoded = htmlentities($data);

    if (
        stripos($data, "hd_src_no_ratelimit:") != false &&
        stripos($data, "aspect_ratio") != false
    ) {
        $start = "hd_src_no_ratelimit:";
        $end = ",aspect_ratio";
        $videoHD = getBetween($data, $start, $end);
    } else {
        $videoHD = false;
    }

    if (
        stripos($data, "sd_src_no_ratelimit:") != false &&
        stripos($data, "aspect_ratio") != false
    ) {
        $start = "sd_src_no_ratelimit:";
        $end = ",aspect_ratio";
        $videoSD = getBetween($data, $start, $end);
    } else {
        $videoSD = false;
    }
    $previous = "javascript:history.go(-1)";
    if (isset($_SERVER['HTTP_REFERER'])) {
        $previous = $_SERVER['HTTP_REFERER'];
    }
    if ($videoHD) {
        header('Location: ' . trim($videoHD, '"'));
    } elseif ($videoSD) {
        header('Location: ' . trim($videoSD, '"'));
    } else {
         ?>
<link
  rel="stylesheet"
  href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
  integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
  crossorigin="anonymous"
/>
<script
  src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
  integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
  crossorigin="anonymous"
></script>
<script
  src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
  integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
  crossorigin="anonymous"
></script>
<script
  src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
  integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
  crossorigin="anonymous"
></script>
<style type="text/css">
  div.body {
    width: 98%;
  }
</style>
<br>
<center>
<div class="body">

      <div class="alert alert-danger" role="alert">
        Can't download content. <a href="<?= $previous ?>">Back</a>
      </div>
</div>
</center>
<?php
    }
}
