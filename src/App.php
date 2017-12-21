<?php

class App
{
    private $options = [];
    private $env = [];
    private $flash_messages = [];

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
        Model::setDb(function() {
            return $this->option('db');
        });

        if ($configure_func) {
            $configure_func->call($this);
        }

        $this->startSession();
        $this->set('flash_messages', function() {
            return $this->getFlashMessages();
        });

        register_shutdown_function(function() {
            $this->writeFlash();
        });

        return $this;
    }

    public function requireLogin()
    {
        if (!isset($_SESSION['user'])) {
            $q = http_build_query(['prev_uri' => $_SERVER['REQUEST_URI']]);
            $this->redirect("/auth?{$q}");
        }

        $this->option('user', function() {
            static $user;
            if ($user) {
                return $user;
            }
            $user = User::find($_SESSION['user']);

            return $user;
        });
        $this->set('user', $this->option('user'));
    }

    private function writeFlash()
    {
        $has_flash = array_key_exists('flash', $_SESSION);
        if ($has_flash || $this->flash_messages) {
            $this->startSession(false);
        }

        if ($has_flash) {
            unset($_SESSION['flash']);
        }
        if ($this->flash_messages) {
            $_SESSION['flash'] = $this->flash_messages;
        }

        if ($has_flash || $this->flash_messages) {
            session_write_close();
        }
    }

    public function flash(string $msg, int $status = 0)
    {
        $this->flash_messages[] = [$msg, $status];
    }

    public function getFlashMessages(): array
    {
        return $_SESSION['flash'] ?? [];
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

    private function startSession(bool $read_and_close = null)
    {
        session_name($this->option('session_name'));
        session_start([
            'read_and_close'  => $read_and_close ?? $this->option('session_read_only'),
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
