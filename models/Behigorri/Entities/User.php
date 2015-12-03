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
* @ORM\Entity
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
}