<?php

$conn = new PDO(
    "mysql:host=localhost;dbname=dulieu1",
    "root",
    ""
);

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>