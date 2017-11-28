<?php

class View
{
    private $vars = [];

    public function set(string $key, $value = null)
    {
        $this->vars[$key] = $value;

        return $this;
    }

    public function render(string $file, string $layout = null, array $vars = [])
    {
        $vars = array_merge($this->getVars(), $vars);
        $content = $this->scopedRender($file, $vars);

        if ($layout === null) {
            return $content;
        }

        return $this->render($layout, null, ['content' => $content]);
    }

    public function partial(string $file, array $vars = [])
    {
        return $this->render($file, null, $vars);
    }

    private function getVars()
    {
        return $this->vars;
    }

    private function scopedRender(string $file, array $vars = [])
    {
        ob_start();
        extract($vars);
        include $file;
        return ob_get_clean();
    }
}
