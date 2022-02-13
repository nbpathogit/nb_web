<!DOCTYPE html>
<html>
<head>
    <title>My website</title>
    <meta charset="utf-8">
</head>
<body>

<h1>Ajax example</h1>

<p>The time is <time id="currentTime"><?= date('g:i:s') ?></time></p>

<button id="btn">Refresh</button>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

<script>
    $("#btn").on("click", function() {

        $.ajax("gettime.php")
            .done(function(data) {
                $("#currentTime").html(data);
            });
    });
</script>

</body>
</html>
