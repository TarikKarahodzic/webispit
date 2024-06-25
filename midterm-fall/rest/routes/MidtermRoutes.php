<?php

Flight::route('GET /connection-check', function () {
    $service = new MidtermService();
});

Flight::route('GET /cap-table', function () {
    $service = new MidtermService();
    $captable = $service->cap_table();
    Flight::json($captable);
});


Flight::route('POST /cap-table-record', function () {
    $share_class_id = Flight::request()->data->share_class_id;
    $share_class_category_id = Flight::request()->data->share_class_category_id;
    $investor_id = Flight::request()->data->investor_id;
    $diluted_shares = Flight::request()->data->diluted_shares;

    $service = new MidtermService();
    $captablerecord = $service->add_cap_table_record($share_class_id, $share_class_category_id, $investor_id, $diluted_shares);

    Flight::json($captablerecord);
});


Flight::route('GET /categories', function () {
    $service = new MidtermService();
    $categories = $service->categories();
    Flight::json($categories);
});

Flight::route("DELETE /investor/@id", function ($id) {
    $service = new MidtermService();
    $service->delete_investor($id);
    Flight::json(['message' => 'Deleted successfully!']);
});
