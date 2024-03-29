<?php
require_once('config.php');
require_once('./models/products.php ');
define('STATUS_INIT', 0); //chờ người dùng xác nhận đơn hàng
define('STATUS_WAITING_FOR_PAYMENT', 1); //chờ người bán xác nhận đã thanh toán
define('STATUS_PREPARING_GOODS', 2); //chờ người bán chuẩn bị đơn hàng
define('STATUS_BEING_TRANSPORTED', 3); //chờ người bán vận chuyển hàng tới người dùng
define('STATUS_COMPLETED', 4); //thành công
define('STATUS_CANCELED', 5); //đã huỷ

    function count_order($from = null, $to = null, $status = null){
        $params = [];
        if(isset($from)){
            $params[] = "create_date >= '$from 00:00:00'";
        }
        if(isset($to)){
            $params[] = "create_date <= '$to 00:00:00'";
        }
        if(isset($status)){
            $params[] = "status = ".$status;
        }
        $params = join(' AND ', $params);
        if($params !== ""){ $params = " WHERE ".$params; }
        $sql = "SELECT COUNT(id) FROM `order`$params";
        $count = run_query($sql, FETCH_ONE);
        return $count[0];
    }

function get_status($satus)
{
    $result = '';
    switch ($satus) {
        case STATUS_INIT:
            $result = 'Chờ người dùng xác nhận đơn hàng';
            break;
        case STATUS_WAITING_FOR_PAYMENT:
            $result = 'Chờ người bán xác nhận đã thanh toán';
            break;
        case STATUS_PREPARING_GOODS:
            $result = 'Chờ người bán chuẩn bị đơn hàng';
            break;
        case STATUS_BEING_TRANSPORTED:
            $result = 'Chờ người bán vận chuyển hàng tới người dùng';
            break;
        case STATUS_COMPLETED:
            $result = 'Thành công';
            break;
        case STATUS_CANCELED:
            $result = 'Đã hủy';
            break;
        default:
            $result = 'Lỗi trong quá trình đặt hàng';
    }
    return $result;
}

    function create_order($cart_data, $customer_id){
        //create order
        $sql = "INSERT INTO `order` (customer_id) VALUES ($customer_id)";
        $stmt = $GLOBALS['connection']->prepare($sql);
        $stmt->execute();
        $current_order_id = $GLOBALS['connection']->lastInsertId();
        //add order detail
        foreach($cart_data['items'] as $key => $product) {
            $order_detail_sql = "INSERT INTO `order_detail` (order_id, product_id, color, size, quantity) VALUES ($current_order_id, {$product['id']}, {$product['data']['color']['id']}, {$product['data']['size']['id']}, {$product['quantity']})";
        }
        $stmt = $GLOBALS['connection']->prepare($order_detail_sql);
        $stmt->execute();
        $sql = "SELECT LPAD(id,10,'0') AS id from `order` WHERE id=$current_order_id";
        $order_id = "CARA".run_query($sql, FETCH_ONE)[0];

        return $order_id;
    }

function update_order($order_id, $order = null, $order_detail = null, $delivery_info = null)
{
    $order_id = intval(str_replace('CARA', '', $order_id));
    try {
        if (isset($order)) {
            $value_data = [];
            foreach ($order as $key => $value) {
                $value_data[] = "$key='$value'";
            }
            $sql = "UPDATE `order` SET " . join(',', $value_data) . " WHERE id=$order_id";
            $sql = run_query($sql, NOT_FETCH);
            if (!$sql) {
                throw new Exception('Update order error');
            }
        }
        if (isset($order_detail)) {
            $del_sql = "DELETE from `order_detail` WHERE order_id=$order_id";
            $del_sql = run_query($del_sql, NOT_FETCH);
            if (!$del_sql) {
                throw new Exception('Delete order detail error');
            }
            foreach ($order_detail['items'] as $key => $product) {
                $sql = "INSERT INTO `order_detail` (order_id, product_id, color, size, quantity) VALUES ($order_id, {$product['id']}, {$product['data']['color']['id']}, {$product['data']['size']['id']}, {$product['quantity']})";
                $order_detail_sql = run_query($sql, NOT_FETCH);
                if (!$order_detail_sql) {
                    throw new Exception('Update order detail error');
                }
            }
        }
        if (isset($delivery_info)) {
            $sql = "SELECT COUNT(id) from `delivery_info` WHERE order_id=$order_id";
            $num = run_query($sql, FETCH_ONE)[0];
            if ($num > 0) {
                $value_data = [];
                foreach ($delivery_info as $key => $value) {
                    $value_data[] = "$key='$value'";
                }
                $sql = "UPDATE `delivery_info` SET " . join(',', $value_data) . " WHERE id=$order_id";
            } else {
                $sql = "INSERT INTO `delivery_info` (order_id,first_name, last_name, email, address, city) VALUES ($order_id, '{$delivery_info['first_name']}', '{$delivery_info['last_name']}', '{$delivery_info['email']}','{$delivery_info['address']}','{$delivery_info['city']}')";
            }
            $sql = run_query($sql, NOT_FETCH);
            if (!$sql) {
                throw new Exception('Update delivery data error');
            }
        }
        return true;
    } catch (\Throwable $th) {
        return $th;
    }
}

function get_order($meta_query = null, $order = null)
{
    $sql = select_query_builder('order', $meta_query, $order);
    $orders = run_query($sql, FETCH_ALL);

    $fmt = [];

    foreach ($orders as $key => $value) {
        $order_detail_sql = select_query_builder('order_detail', array('order_id' => intval($value['id'])), null);
        $order_detail = run_query($order_detail_sql, FETCH_ALL);
        $delivery_info_sql = select_query_builder('delivery_info', array('order_id' => intval($value['id'])), null);
        $delivery_info = run_query($delivery_info_sql, FETCH_ONE);

        $total = 0;


        foreach ($order_detail as $key => $order_detail_value) {
            $product_info = get_product(array('id' => $order_detail_value['product_id']));
            $order_detail[$key]['data'] = $product_info[0];
            $total += $order_detail_value['quantity'] * $product_info[0]['final_price'];
        }

        $fmt[] = array_merge(array(
            'product_data' => $order_detail,
            'delivery_info' => $delivery_info,
            'total' => $total,
            'formatted_total' => number_format($total, 0, '.', '.') . '&#8363;'
        ), $value);
    }

    return $fmt;
}