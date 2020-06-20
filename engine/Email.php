<?php


namespace app\engine;


/**
 * Class Email
 * @package app\engine
 * @property Db $db
 */
class Email
{
    private $email;
    private $db;

    public function __construct($email)
    {
        $this->email = $email;
        $this->db = Db::getInstance();
    }

    public function email(){
        return $this->email;
    }

    public function checkUniqueness()
    {
        $sql = 'SELECT COUNT(*) FROM emails WHERE email= :email';
        return !$this->db->getCount($sql, ['email' => $this->email]);
    }

    public function save()
    {
        if($this->checkUniqueness()) {
            $sql = 'INSERT INTO emails VALUES (NULL, :email, NOW(), NOW())';
            return $this->db->execute($sql, ['email' => $this->email]);
        }
        return false;
    }
}