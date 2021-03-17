<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'cart.php';

session_start();

if (is_logined() === false) {
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);
$order_id = get_post('order_id');
$token =  get_post('token');

if (is_valid_csrf_token($token)) {
  if (is_admin($user) === true) {
    $details = get_details($db, $order_id);
    $history = get_all_history($db, $order_id);
  } else {
    $details = get_user_details($db, $order_id, $user['user_id']);
    $history = get_user_history($db, $user['user_id'], $order_id);
  }
} else {
  set_error('不正な操作が行われました');
  redirect_to(HISTORY_URL);
}

include_once VIEW_PATH . 'details_view.php';
