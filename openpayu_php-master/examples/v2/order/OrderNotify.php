<?php
/**
 * OpenPayU
 *
 * @copyright  Copyright (c) 2014 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 *
 * http://www.payu.com
 * http://developers.payu.com
 * http://twitter.com/openpayu
 *
 */

require_once realpath(dirname(__FILE__)) . '/../../../lib/openpayu.php';
require_once realpath(dirname(__FILE__)) . '/../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $body = file_get_contents('php://input');
    $data = trim($body);

    try {
        if (!empty($data)) {
            $result = OpenPayU_Order::consumeNotification($data);
        }

        if ($result->getResponse()->order->orderId) {

            /* Check if OrderId exists in Merchant Service, update Order data by OrderRetrieveRequest */
            $order = OpenPayU_Order::retrieve($result->getResponse()->order->orderId);

            /* If exists return OrderNotifyResponse */
            $rsp = OpenPayU::buildOrderNotifyResponse($result->getResponse()->order->orderId);

            if (!empty($rsp)) {
                header("Content-Type: application/json");
                echo $rsp;
            }
        }
    } catch (OpenPayU_Exception $e) {
        echo $e->getMessage();
    }
}