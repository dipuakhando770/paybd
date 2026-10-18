<?php
namespace User\Entities;

use CodeIgniter\Entity\Entity;

class User extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function __construct(array $data = null)
    {
        parent::__construct($data);

        if (empty($this->attributes['ids'])) {
            $this->setIds();
            $this->setRef_key();
        }

    }

    public function setEmail($email)
    {
        $this->attributes['email'] = strtolower($email);
        return $this;
    }

    public function setFirstName($first_name)
    {
        $this->attributes['first_name'] = ucwords($first_name);
        return $this;
    }
    
    public function setLastName($last_name)
    {
        $this->attributes['last_name'] = ucwords($last_name);
        return $this;
    }
    
    public function setIds()
    {
        $this->attributes['ids'] = ids();
        return $this;
    }

    public function setRef_key()
    {
        $this->attributes['ref_key'] = ids();
        return $this;
    }

    public function setPassword(string $pass)
    {
        $this->attributes['password'] = password_hash($pass, PASSWORD_BCRYPT);
        return $this;
    }
}
