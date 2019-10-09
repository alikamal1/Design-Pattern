<?php

namespace Behavioral\Strategy;

/**
 * Strategy Design Pattern
 * lets you define a family of algorthims, put each of them into separate class and make their objects interchangable
 */

class Order
{
    private static $orders = [];

    public static function get(int $orderId = null)
    {
        if($orderId === null) {
            return static::$orders;
        } else {
            return static::$orders[$orderId];
        }
    }

    public function __construct(array $attributes)
    {
        $this->id = count(static::$orders);
        $this->status = "new";
        foreach($attributes as $key => $value) {
            $this->{$key} = $value;
        }
        static::$orders[$this->id] = $this;
    }

    public function complete(): void
    {
        $this->status = "completed";
        echo "Order: #{$this->id} is now {$this->status}";
    }
}

class OrderController
{
    // Handle POST Request
    public function post(string $url, array $data)
    {
        echo "Controller: POST request to $url with " . json_encode($data);
        $path = parse_url($url, PHP_URL_PATH);
        if(preg_match("#^/orders?$#", $path, $matches)) {
            $this->postNewOrder($data);
        } else {
            echo "Controller: 404 page";
        }
    }
    // Handle Get Request
    public function get(string $url): void
    {
        echo "Controller: Get request to $url";
        $path = parse_url($url, PHP_URL_PATH);
        $query = parse_url($url, PHP_URL_QUERY);
        parse_url($query, $path);
        if(preg_match("#^/orders?$#", $path, $matches)) {
            $this->getAllOrders();
        } else if(preg_match("#^/order/([0-9]+?)/payment/([a-z]+?)(/return)?$#", $path, $matches)) {
            $order = Order::get($matches[1]);
            $paymentMethod = PaymentFactory::getPaymentMethod($matches[2]);
            if(!isset($matches[3])) {
                $this->getPayment($paymentMethod, $order, $data);
            } else {
                $this->getPaymentReturn($paymentMethod, $order, $data);
            }
        } else {
            echo "Controller: 404 page";
        }
    }
    // POST /order {data}
    public function postNewOrder(array $data): void
    {
        $order = new Order($data);
        echo "Controller: Create the order #{$order->id}";
    }
    // Get /orders
    public function getAllOrders(): void
    {
        echo "Controller: Here's all orders";
        foreach(Order::get() as $order) {
            echo json_encode($order, JSON_PRETTY_PRINT);
        }
    }
    // Get /order/123/payment/XX
    public function getPayment(PaymentMethod $method, Order $order, array $data): void
    {
        $form = $method->getPaymentForm($order);
        echo "Controller: here's the payment form:\n$form";
    }
    // GET /order/123/payment/XXX/return?key=ABCDEFGH&success=true
    public function getPaymentReturn(PaymentMethod $method, Order $order, array $data): void
    {
        try {
            if($method->validateReturn($order, $data)) {
                echo "Controller: Thanks for order";
                $order->complete();
            }
        } catch(\Exception $e) {
            echo "Controller: got an exception $e->getMessage()";
        }
    }
}

class PaymentFactory
{
    public function getPaymentMethod(string $id): PaymentMethod
    {
        switch($id)
        {
            case "cc": return new CreditCardPayment;
            case "paypal": return new PayPalPayment;
            default: throw new \Exception("Unknown Payment Method");
        }
    }
}

interface PaymentMethod
{
    public function getPaymentForm(Order $order): string;
    public function validateReturn(Order $order, array $data): bool;
}

class CreditCardPayment implements PaymentMethod
{
    static private $store_secret_key = "swordfish";
    public function getPaymentForm(Order $order): string
    {
        $returnURL = "https://our-website.com/order/{$order->id}/payment/cc/return";
        return <<<FORM
        <form action="https://my-credit-card-processsor.com/charge" method="POST">
        <input type="hidden" id="email" value="{$order->email}">
        <input type="hidden" id="total" value="{$order->total}">
        <input type="hidden" id="returnURL" value="$returnURL">
        <input type="text" id="cardholder-name"">
        <input type="text" id="credit-card"">
        <input type="text" id="expiration-date"">
        <input type="text" id="ccv-number"">
        <input type="submit" value="Pay"">
        </form>
        FORM;
    }

    public function valudateReturn(Order $order, array $data): bool
    {
        echo "CreditCardPayment: ...validating...";
        if($data["key"] != md5($order->id . static::$store_secret_key)) {
            throw new \Exception("Payment Failed");
        }
        if(floatval($data["total"]) < $order->total) {
            throw new \Exception(("Payment amount is wrong"));
        }
        echo "Done";
        return true;
    }
}

class PayPalPayment implements PaymentMethod
{
    public function getPaymentForm(Order $order): string
    {
        $returnURL = "https://our-website.com/order/{$order->id}/payment/paypal/return";
        return <<<FORM
        <form action="https://paypal.com/payment" method="POST">
        <input type="hidden" id="email" value="{$order->email}">
        <input type="hidden" id="total" value="{$order->total}">
        <input type="hidden" id="returnURL" value="$returnURL">
        <input type="submit" value="Pay on PayPal"">
        </form>
        FORM;
    }

    public function valudateReturn(Order $order, array $data): bool
    {
        echo "PayPalPayment: ...validating...";
        echo "Done";
        return true;
    }
}

$controller = new OrderController;
echo "Client: Let's create some orders";

$controller->post("/orders", [
    "email" => "me@example.com",
    "product" => "ABC cat food",
    "total" => 9.95,
]);

$controller->post("/orders", [
    "email" => "me@example.com",
    "product" => "XYZ cat food",
    "total" => 19.95,
]);

echo "Client: List my orders, please";
$controller->get("/orders");

echo "Client: I'd like to pay for the second, show me the payment form";
$controller->get("/order/1/payment/paypal");

echo "Client: pushes the pay button";
echo "Client: redirected to paypal";
echo "Client: pay on Paypal";
echo "Client: back to website";

$controller->get("/order/1/payment/paypal/return?key=ABCDEFGHJ&success=true&total=19.95");

/*
Client: Let's create some orders
Controller: POST request to /orders with {"email":"me@example.com","product":"ABC Cat food (XL)","total":9.95}
Controller: Created the order #0.
Controller: POST request to /orders with {"email":"me@example.com","product":"XYZ Cat litter (XXL)","total":19.95}
Controller: Created the order #1.

Client: List my orders, please
Controller: GET request to /orders
Controller: Here's all orders:
{
    "id": 0,
    "status": "new",
    "email": "me@example.com",
    "product": "ABC Cat food (XL)",
    "total": 9.95
}
{
    "id": 1,
    "status": "new",
    "email": "me@example.com",
    "product": "XYZ Cat litter (XXL)",
    "total": 19.95
}

Client: I'd like to pay for the second, show me the payment form
Controller: GET request to /order/1/payment/paypal
Controller: here's the payment form:
<form action="https://paypal.com/payment" method="POST">
    <input type="hidden" id="email" value="me@example.com">
    <input type="hidden" id="total" value="19.95">
    <input type="hidden" id="returnURL" value="https://our-website.com/order/1/payment/paypal/return">
    <input type="submit" value="Pay on PayPal">
</form>

Client: ...pushes the Pay button...

Client: Oh, I'm redirected to the PayPal.

Client: ...pays on the PayPal...

Client: Alright, I'm back with you, guys.
Controller: GET request to /order/1/payment/paypal/return?key=c55a3964833a4b0fa4469ea94a057152&success=true&total=19.95
PayPalPayment: ...validating... Done!
Controller: Thanks for your order!
Order: #1 is now completed.
*/