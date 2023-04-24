<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{page_title}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link href="/Arcee/src/kendoui/css/kendo.bootstrap.min.css" rel="stylesheet">
    <link href="/Arcee/src/kendoui/css/kendo.common-bootstrap.min.css" rel="stylesheet">
    <link href="/Arcee/src/fontawesome/css/all.min.css" rel="stylesheet">
    <link href="/Arcee/src/css/bootstrap-custom.css" rel="stylesheet">
    {{dynamic_css}}
    <script src="/Arcee/src/kendoui/js/jquery.min.js"></script>
    <script src="/Arcee/src/kendoui/js/kendo.all.min.js"></script>
    <script src="/Arcee/src/kendoui/js/jszip.min.js"></script>
  </head>
  <body>
    <?php
    require_once("default_init.php");
    require_once("default_ajax.php");
    require_once("default_navbar.php");
    require_once("default_sidebar.php");
    ?>
    <div id="content" class="container">
      {{content}}
    </div>

  </body>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
  <script src="/Arcee/src/kendoui/js/jszip.min.js"></script>
  <script src="/Arcee/src/js/default.js"></script>
  {{dynamic_js}}
</html>

