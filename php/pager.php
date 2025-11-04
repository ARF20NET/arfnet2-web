<?php

$auth = "ea5jgx:KjNHWkLpy3XaJv2N7WIZ";//"callsign:password";
$url = "http://www.hampager.de:8080/calls";

if (isset($_GET["msg"])) {
    $data = [
        'text' => $_GET["msg"],
        'callSignNames' => array( 'ea5jgx' ),
        'transmitterGroupNames' => array( 'ea-all' ),
        'emergency' => 'false'
    ];

    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-type: application/json\r\n".
                "Authorization: Basic ".base64_encode("$auth")."\r\n",
            'content' => json_encode($data),
        ],
    ];

    $context  = stream_context_create($options);

    $response = file_get_contents($url, false, $context);
}

?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="https://arf20.com/style.css">
        <title>ARFNET</title>
    </head>

    <body>
        <header><a href="/">
            <img src="http://arf20.com/arfnet_logo.png" width="64"><span class="title"><strong>ARFNET</strong></span>
        </a></header>
        <hr>
        <main>
            <h2 class="center">Pager service</h2>
            <form action="/php/pager.php" method="GET">
                <h3>Page arf20!</h3>
                <input type="text" name="msg">
                <input type="submit" value="Page"><br>
<?php 
if (isset($response)) {
    if ($response === false)
        echo "<span>Error</span>";
    else
        echo "<pre>$response</pre>";
}
?>
            </form>
        </main>
    </body>
</html>
