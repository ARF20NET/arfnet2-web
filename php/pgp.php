<?php

$result = null;

if (isset($_GET["op"])) {
    if (!file_exists('/tmp/gpg')) {
        mkdir('/tmp/gpg', 0700, true);
        $result = shell_exec("gpg --homedir /tmp/gpg --import --trust-model always ../arf20.ecc.asc 2>&1");
        echo "<pre>$result</pre>";
        $result = shell_exec("echo \"071FD0F02A0292F08EE1A121EB74F0C93E429F8E:5\n6DDA5B0BF8F154C4C0542B92DEBF11CAEEFA5962:5\" | gpg --homedir /tmp/gpg --import-ownertrust 2>&1");
        echo "<pre>$result</pre>";

    }
    $data = $_GET["data"];
    if ($_GET["op"] == "Verify") {
        if (preg_match("[^a-zA-Z0-9+/=- ]+", $data) == 1)
            die("invalid message");
        $result = shell_exec("echo \"".$data."\" | gpg --homedir /tmp/gpg --no-tty --trust-model always --verify 2>&1");
    } else if ($_GET["op"] == "Encrypt") {
        if (preg_match("[\"\''\\]+", $data) == 1)
            die("invalid character(s)");

        $result = shell_exec("echo \"".$data."\" | gpg --homedir /tmp/gpg --no-tty --always-trust --encrypt --armor -r 071FD0F02A0292F08EE1A121EB74F0C93E429F8E 2>&1");
    }
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
            <h2 class="center">OpenPGP</h2>
            <form action="/php/pgp.php" method="GET">
                <h3>Verify something arf20 said, or encrypt something for arf20</h3>
                <textarea name="data" rows="25" cols="80"><?php echo $_GET["data"]; ?></textarea><br>
                <input type="submit" name="op" value="Verify"><input type="submit" name="op" value="Encrypt"><br>

            </form>
<?php 
if (isset($result)) {
    if ($result === null) {
        echo "Null";
    } else if ($result === false) {
        echo "False";
    } else {
        echo "<textarea readonly rows=\"25\" cols=\"80\">\n$result\n</textarea>";
    }
} else echo "Unset";
?>
        </main>
    </body>
</html>
