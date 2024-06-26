<?php

Flight::route('GET /midterm/connection-check', function () {
    /** TODO
     * This endpoint prints the message from constructor within MidtermDao class
     * Goal is to check whether connection is successfully established or not
     * ENDPOINT IS NOT GRADED; YOU CAN USE IT IF YOU NEED TO TEST CONNECTION, OTHERWISE YOU CAN LEAVE IT EMPTY
     * This endpoint does not have to return output in JSON format
     */
    $service = new MidtermService();
});

Flight::route('POST /midterm/input_data', function () {
    /** TODO
     * This endpoint is used to insert IP2LOCATION.json file content to database table locations
     * This endpoint should return output in JSON format
     * 10 points
     */

    $filename = '../IP2LOCATION.json';

    // Read the JSON file in PHP
    $data = file_get_contents($filename);
    // Convert the JSON String into PHP Array
    $array = json_decode($data, true);
    foreach ($array as $instance) {
        $payload = $instance;
        $midtermService = new MidtermService();
        Flight::json($midtermService->input_data($payload));
    }
});


Flight::route('GET /midterm/summary/@country', function ($country) {
    /** TODO
     * This endpoint is used to return total number of regions and cities from locations table
     * by country given as parameter
     * This endpoint should return output in JSON format
     * 30 points
     */

    $service = new MidtermService();
    $summary = $service->summary($country);
    Flight::json($summary);
});

Flight::route("GET /midterm/encoded", function () {
    /** TODO
     * This endpoint is used to create report that lists first 10 countries and their hashed values
     * Sample data for one country: ['contry' => 'United States', 'encoded' => 'VW5pdGVkIFN0YXRlcw=='];
     * There is php function used to encode string
     * This endpoint should return output in JSON format
     * 30 points
     */

    $service = new MidtermService();
    $encoded = $service->encoded();
    Flight::json($encoded);
});

Flight::route("GET /midterm/@ip", function($ip){
    /** TODO
    * This endpoint is used to return location(s) based on ip address given as parameter
    * There is a php function to convert ip address to number, therefore you can use this number to
    * match with those 'from' and 'to' in database
    * This endpoint should return output in JSON format
    * 30 points
    */
    $ipNumber = ip2long($ip);  // ipThailand = 1.0.177.0
    $midtermService = new MidtermService();
    Flight::json($midtermService->ip($ipNumber));
});
