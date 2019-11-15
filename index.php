<?php

use pseph\nff\framework\RouteToRouter;

require_once "commonErrorHandler.php";

$routetoRouter = new RouteToRouter($_SERVER["REQUEST_URI"]);
