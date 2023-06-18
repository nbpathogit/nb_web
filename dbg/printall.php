<?php
session_start();

echo '==Start vardump SESSION==<br>';
if (isset($_SESSION)) {
    echo '$_SESSION was set.' . '<br>';
    echo '<pre>';
    var_dump($_SESSION);
    echo '</pre><br>';
} else {
    echo '$_SESSION was Null.' . '<br>';
    //echo var_dump($_SESSION) . '<br>';
}
echo '==End vardump SESSION==<br>';