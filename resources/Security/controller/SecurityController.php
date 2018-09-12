<?php
namespace App\Controller;

use Fenxweb\Fenx\Controller;
use Fenxweb\Fenx\Security\AuthManager;
use Fenxweb\Fenx\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Session\Session;
use Fenxweb\Fenx\Templating\Engine;
use Fenxweb\Fenx\Annotation as Fenx;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller {
    /**
     * @Fenx\Inject(var="fb",service="form")
     * @Fenx\Inject(var="auth",service="auth")
     * @Fenx\Inject(var="session",service="session")
     * @Fenx\Inject(var="view",service="templating")
     */
    public function login(Request $request, Engine $view, AuthManager $auth, Session $session, FormBuilder $fb ) {
        if($auth->check()) {
            $session->getFlashBag()->add('notice','You are already connected.');
            return $this->redirect($auth->getRedirectTo());
        }

        $form = $fb->template('Security/login_form.php');
        if($form->posted()) {
            $errors = $form->check();
            if(!$errors) {
                $result = $auth->login($form->getValue("username"), $form->getValue("password"));
                if($result === AuthManager::LOGIN_SUCCESS) {
                    if($request->query->has('redirectTo')) {
                        return $this->redirect($request->query->get('redirectTo'));
                    }
                    return $this->redirect($auth->getRedirectTo());
                }else{
                    $session->getFlashBag()->add('error','Bad credentials');
                }
            }else{
                foreach($errors as $e) {
                    $session->getFlashBag()->add('error',$e);
                }
            }
        }
        return $view->render('Security/login.php', ['form' => $form]);
    }
    /**
     * @Fenx\Inject(var="auth",service="auth")
    */
    public function logout(AuthManager $auth) {
        $auth->logout();
        return $this->redirect($auth->getRedirectTo());
    }
}