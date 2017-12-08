<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Exceptions\CashOutBalanceException;
use App\Models\ModelUser;

/**
 * Контроллер личного кабинета и страницы входа
 *
 * @package App\Controllers
 */
class ControllerMain extends Controller
{
    /**
     * @inheritdoc
     */
    public function __construct($router)
    {
        $this->middleware('csrf', ['login', 'cashout']);
        $this->middleware('auth', ['personal', 'cashout']);
        parent::__construct($router);
    }

    /**
     * Страница входа
     *
     * @throws \App\Exception\LayoutNotFoundException
     * @throws \App\Exception\TemplateNotFoundException
     */
    public function index()
	{
        $this->view->generate('login', 'layout-login');
	}

    /**
     * Действие входа, передаются данные с формы входа
     */
	public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $username = $_POST['username'];
        $password = $_POST['password'];

        $model = new ModelUser();
        $user = $model->getUser($username);
        if (empty($user)) {
            $this->router->redirect('/', 'User not found');
            return;
        }

        $passwordHashed = $user->password;
        if (password_verify($password, $passwordHashed)) {
            Session::write('user', serialize($user));
            Session::commit();
            $this->router->redirect('/main/personal');
        } else {
            $this->router->redirect('/', 'Wrong password');
        }
    }

    /**
     * Персональный кабинет
     *
     * @throws \App\Exception\LayoutNotFoundException
     * @throws \App\Exception\TemplateNotFoundException
     */
    public function personal()
    {
        $user = get_user();

        $this->view->generate('personal', 'layout', ['user' => $user]);
    }

    /**
     * Действие вывода средств
     */
    public function cashout()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->router->redirect('/main/personal', 'Unknown method');
            return;
        }

        $userModel = new ModelUser();
        $cashOutSum = doubleval($_POST['sum']);

        $user = get_user();

        try {
            $newBalance = $userModel->cashOutUserBalance($user, $cashOutSum);
        } catch (CashOutBalanceException $e) {
            $this->router->redirect('/main/personal', $e->getMessage());
            return;
        }

        $user->balance = $newBalance;
        Session::write('user', serialize($user));
        Session::close();

        $this->router->redirect('/main/personal', null, 'Success cash out');
    }
}