<?php

require_once __DIR__.'/../../../vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class User {

    public $id;
    public $username;
    public $password;

    public function __construct($id, $username, $password) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
    }

    static function load($name) {
        $user = NULL;
        $file_path = __DIR__ . '/../Resources/Users/' . strtolower($name) . '.yaml';
        if (file_exists($file_path)) {
            try {
                $user_data = Yaml::parse(file_get_contents($file_path));
                $user = new User($user_data['id'], $user_data['username'], $user_data['password']);
            }
            catch (ParseException $e) {
                printf("Unable to parse the YAML string: %s", $e->getMessage());
            }
        }
        return $user;
    }
}