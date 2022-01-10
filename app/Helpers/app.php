<?php

function redirect($route) {
    header('Location: ' . URL_PATH . $route);
}