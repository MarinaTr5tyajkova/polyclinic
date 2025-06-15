<?php
namespace Src;

use Exception;

class View {
    private string $view = '';
    private array $data = [];
    private string $root = '';
    private string $layout = '/layouts/main.php';

    public function __construct(string $view = '', array $data = []) {
        $this->root = $this->getRoot();
        $this->view = $view;
        $this->data = $data;
    }

    private function getRoot(): string {
        global $app;
        $root = $app->settings->getRootPath();
        $path = $app->settings->getViewsPath();
        return $_SERVER['DOCUMENT_ROOT'] . $root . $path;
    }

    private function getPathToMain(): string {
        return $this->root . $this->layout;
    }

    private function getPathToView(string $view = ''): string {
        $view = str_replace('.', '/', $view);
        $path = $this->getRoot() . "/$view.php";
        return $path;
    }

    public function render(string $view = '', array $data = []): string {
        $path = $this->getPathToView($view);
        if (!file_exists($path)) {
            throw new Exception("Template file not found: $path");
        }
        if (!file_exists($this->getPathToMain())) {
            throw new Exception("Layout file not found: " . $this->getPathToMain());
        }

        extract($data, EXTR_PREFIX_SAME, '');
        ob_start();
        require $path;
        $content = ob_get_clean();
        return require($this->getPathToMain());
    }

    public function __toString(): string {
        return $this->render($this->view, $this->data);
    }
}
