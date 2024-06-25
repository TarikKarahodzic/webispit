<?php

Flight::route('GET /connection-check', function(){
    $service = new MidtermService();
});

Flight::route('GET /cap-table', function(){
    $service = new MidtermService();
    $captable = $service->cap_table();
    Flight::json($captable);
});

Flight::route('GET /summary', function(){
    $service = new MidtermService();
    $summary = $service->summary();
    Flight::json($summary);
});

Flight::route('GET /investors', function(){
    $service = new MidtermService();
    $investors = $service->investors();
    Flight::json($investors);
});