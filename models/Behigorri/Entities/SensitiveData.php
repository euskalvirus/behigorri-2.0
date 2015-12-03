<?php
namespace Behigorri\Entities;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(
 *   name="SensitiveData",
 *   options={
*       "collate"="utf8_general_ci", "charset"="utf8"
*    }
 * )
 */
class SensitiveData
{
    /**
     * @ORM\Id
    * @ORM\Column(type="integer", options={"unsigned":true})
    * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="sensitiveDatas")
     * @ORM\JoinColumn(name="ownerId", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="sensitiveDatas")
     * @ORM\JoinTable(name="SensitiveDataTag")
     */
    private $tags;
    
    /**
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="sensitiveDatas")
     * @ORM\JoinTable(name="SensitiveDataGroup")
     */
    private $groups;
    
    public function __construct() {
        $this->tags = new ArrayCollection();
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