<?php

use Phalcon\Mvc\Controller;

class OrderController extends Controller
{
    public function indexAction()
    {
//        if($this->session->has("user")){
//            $name = $this->session->get("user");
//            $this->view->setVar("name", $name);
//        }else{
//            $this->view->setVar("name", 'Ты кто такой?');
//        };
    }

    // реализовать : Данный запрос должен выполняться через Gearman как фоновый процесс.
    public function addOrderAction()
    {

        if ($this->request->isPost()) {

            $order = new Orders([
                'passanger_id' => $this->request->getPost('passanger_id'),
                'access_token' => $this->request->getPost('access_token'),
                'user_location' => serialize($this->request->getPost('user_location')),
                'car_id' => $this->request->getPost('car_id'),
                'driver_id' => $this->request->getPost('driver_id'),
                'country_id' => $this->request->getPost('country_id'),
                'pass_phone' => $this->request->getPost('pass_phone'),
                'region_id' => $this->request->getPost('region_id'),
                'route_points' => serialize($this->request->getPost('route_points')),
                'start_time' => $this->request->getPost('start_time'),
                'pass_count' => $this->request->getPost('pass_count'),
                'callme' => $this->request->getPost('callme'),
                'large' => $this->request->getPost('large'),
                'pets' => $this->request->getPost('pets'),
                'wishlist_option_id' => serialize($this->request->getPost('wishlist_option_id')),
                'baby_chair' => $this->request->getPost('baby_chair'),
                'payment_type_id' => $this->request->getPost('payment_type_id'),
                'deffered_payment' => $this->request->getPost('deffered_payment'),
                'duration' => $this->request->getPost('duration'),
                'extension' => $this->request->getPost('extension'),
                'comment' => $this->request->getPost('comment'),
            ]);
            if (!$order->save()) {
                //$this->flash->error($order->getMessages());
            } else {
                $this->response->setJsonContent(array('order_id' => $order->id, 'order_status_id' => intval($order->status), 'order_status' => "Ожидает ответ водителя"));
                $this->response->send();
                //уведомляем водителя о новом заказе
            }
        }

    }

    public function setOrderStatusAction()
    {
        if ($this->request->isPut()) {
            //- access token E@3dkCRjzjN9pskGA2~Ya4?mmPgwvI{K82yz

            $order_id = $this->request->getPut("order_id");
            $driver_id = $this->request->getPut("driver_id");

            $order = Orders::findFirst(
                [
                    "id = :order_id: AND driver_id = :driver_id:",
                    "bind" => [
                        "order_id" => $order_id,
                        "driver_id" => $driver_id,
                    ],
                ]
            );

            //насколько я понял order_status_id это код кнопки которая отрабатывает по нажатию подителя, код нового статуса...
            $status = $this->request->getPut("order_status_id");

            if($order->status == 0 && $status == 1 || $status == 5){
                $order->status = $status;
                $order->save();
                //уведомляем заказчика о решении водителя
                $this->response->setJsonContent(array('success' => $status));
                $this->response->send();
            } elseif($order->status >= 1 && $order->status == $status-1 && $order->status <= 4 ){
                $order->status = $status;
                $order->save();
                //if($status == 2)  уведомляем клиента о подаче авто
                $this->response->setJsonContent(array('success' => $status));
                $this->response->send();
            } else {
                //ошибки
            }

            //Реализовать:
            //Клиент может установить только статус 6 - отмена заказа, отмена возможна в период с 1 по 3 этапы,
            //после того как водитель установил статус 3 отмена заказа невозможна.
            //Проверки должны быть как в мобильных клиентах так и на стороне сервера.

        }
    }

    public function pushToDriver($id, $order_id)
    {
        //каким-то образом делается отправка push сообщения на устройство данного водителя,
        //в своем приложении водитель должен принять заказ или отклонить.
    }

    public function getMapInfoAction()
    {
        if ($this->request->isGet()) {
            //$this->request->getQuery('user_location');
            //$this->request->getQuery('access_token');

            $cars = Cars::find(" status = '1' ");
            //$cars->location = unserialize($cars->location);
            $this->response->setJsonContent(array('cars' => $cars));
            $this->response->send();
        }
    }

}