<?php

use Phalcon\Mvc\Controller;

class RegistrationController extends Controller
{
    public function indexAction()
    {
        if ($this->request->isPost()) {
            $user = new Users([
                'name' => $this->request->getPost('name'),
                'key' => $this->request->getPost('key'),
                'password' => md5($this->request->getPost('password')),
                'country_id' => $this->request->getPost('country_id'),
                'phone' => $this->request->getPost('phone')
            ]);
            if (!$user->save()) {
                //$this->flash->error($user->getMessages());
            } else {
                //сохраняем access_token в redis по ключу AccessToken с hash id пользователя value токен.
                //$cache->save('AccessToken', [ $user->id => $user->key ]);

                $this->response->setJsonContent(array('user_id' => $user->id, 'access_token' => $user->key));
                $this->response->send();
            }
        }

    }

}