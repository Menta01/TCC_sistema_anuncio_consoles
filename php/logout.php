<?php

if(!isset($_SESSION)) {
    session_start();
}

session_destroy();

header(": html/tela_Login.html");