<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::route('GET /final/connection-check', function () {
  /** TODO
   * This endpoint prints the message from constructor within MidtermDao class
   * Goal is to check whether connection is successfully established or not
   * This endpoint does not have to return output in JSON format
   */
  $service = new MidtermService();
});

Flight::route('POST /final/login', function () {
  /** TODO
   * This endpoint is used to login user to system
   * you can use email: demo.user@gmail.com and password: 123 to login
   * Output should be array containing success message and JWT for this user
   * Sample output is given in figure 7
   * This endpoint should return output in JSON format
   */
  $data = Flight::request()->data->getData();
  $email = $data['email'];
  $password = $data['password'];

  $service = new FinalService();
  $user = $service->login($email, $password);

  if ($user) {
    $key = "your_secret_key"; // secret key for JWT
    $payload = array(
      "iss" => "http://localhost.com",
      "aud" => "http://localhost.com",
      "iat" => time(),
      "nbf" => time() + 10,
      "data" => [
        "id" => $user['id'],
        "email" => $user['email']
      ]
    );

    $jwt = JWT::encode($payload, $key, 'HS256');
    Flight::json(['message' => 'Login successful', 'token' => $jwt]);
  } else {
    Flight::json(['message' => 'Login failed'], 401);
  }
});

Flight::route('POST /final/investor', function () {
  /** TODO
   * This endpoint is used to add new record to investors and cap-table database tables.
   * Investor contains: first_name, last_name, email and company
   * Cap table fields are share_class_id, share_class_category_id, investor_id and diluted_shares
   * RULE 1: Sum of diluted shares of all investors within given class cannot be higher than authorized assets field
   * for share class given in share_classes table
   * Example: If share_class_id = 1, sum of diluted_shares = 310 and authorized_assets for this share_class = 500
   * It means that investor added to cap table with share_class_id = 1 cannot have more than 190 diluted_shares
   * RULE 2: Email address has to be unique, meaning that two investors cannot have same email address
   * If added successfully output should be the message that investor has been created successfully
   * If error detected appropriate error message should be given as output
   * This endpoint should return output in JSON format
   * Sample output is given in figure 2 (message should be updated according to the result)
   */
  $first_name = Flight::request()->data->first_name;
  $last_name = Flight::request()->data->last_name;
  $email = Flight::request()->data->email;
  $company = Flight::request()->data->company;
  $created_at = Flight::request()->data->created_at;

  $service = new MidtermService();
  $investor = $service->investor($first_name, $last_name, $email, $company, $created_at);
  Flight::json($investor);
});


Flight::route('GET /final/share_classes', function () {
  /** TODO
   * This endpoint is used to list all share classes from share_classes table
   * This endpoint should return output in JSON format
   */
  $service = new FinalService();
  $shareClasses = $service->share_classes();
  Flight::json($shareClasses);
});

Flight::route('GET /final/share_class_categories', function () {
  /** TODO
   * This endpoint is used to list all share class categories from share_class_categories table
   * This endpoint should return output in JSON format
   */
  $service = new FinalService();
  $shareCC = $service->share_class_categories();
  Flight::json($shareCC);
});
