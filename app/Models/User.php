<?php

class User {

    public $id;
    public $name;
    public $surname;
    public $nickname;
    public $phone;
    public $email;
    public $password;

    public function __construct($id, $name, $surname, $nickname, $phone, $email, $password) {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->nickname = $nickname;
        $this->phone = $phone;
        $this->email = $email;
        $this->password = $password;
        $this->role = 'user'; // Default role
    }

}
