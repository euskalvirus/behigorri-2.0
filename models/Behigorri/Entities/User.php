<?php

namespace Behigorri\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
*
* @ORM\Entity(repositoryClass="Behigorri\Repositories\UserRepository")
* @ORM\Table(
*   name="User",
*   options={
*     "collate"="utf8_general_ci", "charset"="utf8"
*   }
* )
*/
class User implements AuthenticatableContract, CanResetPasswordContract, AuthorizableContract
{

   use Authenticatable, Authorizable, CanResetPassword;

   /**
    * @ORM\Id
    * @ORM\Column(type="integer", options={"unsigned":true})
    * @ORM\GeneratedValue(strategy="IDENTITY")
    *
    * @var  int
    */
   private $id;
   
   /**
    * @ORM\Column(type="string", nullable=true)
    * @var string
    */
   private $name;

   /**
    *
    * @ORM\Column(type="string", length=255, unique=true, nullable=false)
    * @var string
    */
   private $email;

   /**
    * @ORM\Column(type="string", length=255, nullable=false)
    * @var string
    */
   private $password;
   
   /**
    * @ORM\OneToMany(targetEntity="SensitiveData", mappedBy="user")
    */
   private $sensitiveDatas;

   /**
    * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
    * @ORM\JoinTable(name="UserGroup")
    */
   private $groups;
   
   public function __construct() {
       $this->sensitiveDatas = new ArrayCollection();
       $this->groups = new ArrayCollection();
   }

   /**
    * @ORM\Column(type="string", length=300, nullable=true)
    */
   private $createdAt;
   
   /**
    * @ORM\Column(type="string", length=300, nullable=true)
    */
   private $updatedAt;
   

   /**
    * @var string
    * @ORM\Column(type="string", length=355, nullable=true)
    */
   private $token;
   
   /**
    * @var boolean
    * @ORM\Column(type="boolean", options = {"default":0}, nullable=false)
    */
   private $userActive;

    /**
    * @var boolean
    * @ORM\Column(type="boolean", options = {"default":0}, nullable=false)
    */
    private $god;
    
    /**
     * @var string
     * @ORM\Column(type="string", length=355, nullable=true)
     */
    private $salt;
    
    /**
     * @var string
     * @ORM\Column(type="string", length=355, nullable=true)
     */
    private $activationCode;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set createdAt
     *
     * @param string $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param string $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Add sensitiveData
     *
     * @param \Behigorri\Entities\SensitiveData $sensitiveData
     *
     * @return User
     */
    public function addSensitiveData(\Behigorri\Entities\SensitiveData $sensitiveData)
    {
        $this->sensitiveDatas[] = $sensitiveData;

        return $this;
    }

    /**
     * Remove sensitiveData
     *
     * @param \Behigorri\Entities\SensitiveData $sensitiveData
     */
    public function removeSensitiveData(\Behigorri\Entities\SensitiveData $sensitiveData)
    {
        $this->sensitiveDatas->removeElement($sensitiveData);
    }

    /**
     * Get sensitiveDatas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSensitiveDatas()
    {
        return $this->sensitiveDatas;
    }

    /**
     * Add group
     *
     * @param \Behigorri\Entities\Group $group
     *
     * @return User
     */
    public function addGroup(\Behigorri\Entities\Group $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove group
     *
     * @param \Behigorri\Entities\Group $group
     */
    public function removeGroup(\Behigorri\Entities\Group $group)
    {
        $this->groups->removeElement($group);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }
    
    public function getAuthIdentifierName()
    {
        return 'id';
    }
    
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }
    
    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->token;
    }
    
    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->token = $value;
    }
    
    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_me_token';
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set god
     *
     * @param boolean $god
     *
     * @return User
     */
    public function setGod($god)
    {
        $this->god = $god;

        return $this;
    }

    /**
     * Get god
     *
     * @return boolean
     */
    public function getGod()
    {
        return $this->god;
    }

    
    /**
     * Set token
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
    	$this->salt = $salt;
    
    	return $this;
    }
    
    /**
     * Get token
     *
     * @return string
     */
    public function getSalt()
    {
    	return $this->salt;
    }
    
    /**
     * Set userActive
     *
     * @param boolean $userActive
     *
     * @return User
     */
    public function setUserActive($userActive)
    {
    	$this->userActive = $userActive;
    
    	return $this;
    }
    
    /**
     * Get userActive
     *
     * @return boolean
     */
    public function getUserActive()
    {
    	//var_dump((bool)$this->userActive);exit;
    	return $this->userActive;
    }
    
    /**
     * Set token
     *
     * @param string $activationCode
     *
     * @return User
     */
    public function setActivationCode($activationCode)
    {
    	$this->activationCode = $activationCode;
    
    	return $this;
    }
    
    /**
     * Get token
     *
     * @return string
     */
    public function getActivationCode()
    {
    	return $this->activationCode;
    }
    
    
    
    
    
    

    public function getUniqueSensitiveData()
    {
        $sensitiveDatas = [];
        foreach ($this->getGroups() as $group)
        {
            foreach ($group->getSensitiveDatas() as $data)
            {
                if (!isset($sensitiveDatas[$data->getId()])) {
                    $sensitiveDatas[$data->getId()] = $data;
                }
            }
        }
        

        foreach ($this->getSensitiveDatas() as $data)
        {
            if (!isset($sensitiveDatas[$data->getId()])) {
                $sensitiveDatas[$data->getId()] = $data;
            }
        }
        
        return $sensitiveDatas;
    }
    
    public function canBeViewSenstiveData($id)
    {
        foreach ($this->getUniqueSensitiveData()as $data)
        {
            if($data->getId() == $id)
            {
                //var_dump($data->getId());
                return true;
            }
        }
        
        return false;
    }
    
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}