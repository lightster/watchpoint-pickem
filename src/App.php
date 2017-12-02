<?php

class App
{
    private $options = [];
    private $env = [];

    public function option(string $key, $value = null)
    {
        if (count(func_get_args()) === 1) {
            if (is_callable($this->options[$key])) {
                return $this->options[$key]->call($this);
            }
            return $this->options[$key];
        }
        $this->options[$key] = $value;
    }

    public function db()
    {
        return $this->option('db');
    }

    public function run(Closure $configure_func = null)
    {
        $this->loadEnv();
        $this->option('view', new View);
        $this->option('session_name', 'watchpoint-io');
        $this->option('session_read_only', true);
        $this->option('db', function () {
            static $db;
            if ($db) {
                return $db;
            }
            $db = new Db(getenv('DB_URL'));
            return $db;
        });

        if ($configure_func) {
            $configure_func->call($this);
        }

        $this->startSession();

        return $this;
    }

    private function loadEnv()
    {
        $env_file = __DIR__ . '/../.env.php';
        if (!file_exists($env_file)) {
            return;
        }
        $env = include $env_file;
        foreach ($env as $key => $value) {
            putenv("{$key}={$value}");
        }
    }

    private function startSession()
    {
        session_name($this->option('session_name'));
        session_start([
            'read_and_close'  => $this->option('session_read_only'),
        ]);
    }

    public function set(string $key, $value = null)
    {
        return $this->option('view')->set($key, $value);
    }

    public function render(string $file, $layout = null, array $vars = [])
    {
        return $this->option('view')->render($file, $layout, $vars);
    }

    public function redirect(string $uri)
    {
        header("Location: {$uri}");
        exit(0);
    }
}
