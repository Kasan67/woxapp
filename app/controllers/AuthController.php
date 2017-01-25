<?php

use Phalcon\Mvc\Controller;

class AuthController extends Controller
{
    public function indexAction()
    {
        if ($this->request->isPost()) {
            $phone = $this->request->getPost('phone');
            $password = md5($this->request->getPost('password'));
            $user = Users::findFirst(
                [
                    "phone = :phone: AND password = :password:",
                    "bind" => [
                        "password" => $password,
                        "phone" => $phone,
                    ],
                ]
            );

            if (!$user) {
                $this->flash->error($user->getMessages());
            } else {
                $this->flash->success("User was created successfully");
                $this->response->setJsonContent(array('user_id' => $user->id, 'access_token' => $password, 'user_status' => $user->status ));
                $this->response->send();
            }
        }
    }

    public function logoutAction()
    {
        // Полная очистка сессииs
        $this->session->destroy();
    }
}