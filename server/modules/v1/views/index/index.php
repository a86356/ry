<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>houtan</title>
</head>
<body>
<div id="av">123</div>

<!--<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>-->

<?=$this->registerJsFile("@web/js/jquery2.0.0.js"); ?>
<script>

    (function () {
        console.log($("#av").html())
    })();

</script>
</body>
</html>