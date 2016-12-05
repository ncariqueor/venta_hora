<?php

ini_set("max_execution_time", 0);

$venta = new mysqli('localhost', 'root', '', 'ventahora');

$query = "select codsku, numorden from ingresos where fechant = 20161107";