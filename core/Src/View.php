<?php

namespace Src;

class View
{
   private string $view;
   private array $params;

   public function __construct(string $view = '', array $params = [])
   {
       $this->view = $view;
       $this->params = $params;
   }

   public function render(string $view, array $params = []): string
   {
       $this->view = $view;
       $this->params = $params;
       return $this->renderInternal();
   }

   private function renderInternal(): string
   {
       $viewPath = __DIR__ . '/../../views/' . str_replace('.', '/', $this->view) . '.php';
       $layoutPath = __DIR__ . '/../../views/layouts/main.php';
       if (!file_exists($viewPath)) {
           return '';
       }
       extract($this->params, EXTR_OVERWRITE);
       ob_start();
       include $viewPath;
       $content = ob_get_clean() ?: '';

       if (file_exists($layoutPath)) {
           ob_start();
           include $layoutPath;
           return ob_get_clean() ?: $content;
       }
       return $content;
   }
}


