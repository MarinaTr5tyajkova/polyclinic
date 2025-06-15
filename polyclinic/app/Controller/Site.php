<?php
namespace Controller;

use Src\View;

class Site {
    public function index(): string {
        $view = new View();
        return $view->render('site.hello', ['message' => 'Welcome to the Home Page!']);
    }

    public function hello(): string {
        $view = new View();
        return $view->render('site.hello', ['message' => 'This is the Hello Page!']);
    }
}
