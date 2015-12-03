<?php
namespace Behigorri\Entities;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *   name="Group",
 *   options={
*      "collate"="utf8_general_ci", "charset"="utf8"
*   })
 */
class Group
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
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $description;
    
    // ...
    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groups")
     */
    private $users;
    
    /**
     * @ORM\ManyToMany(targetEntity="SensitiveData", mappedBy="groups")
     */
    private $sensitiveDatas;
    
    public function __construct() {
        $this->users = new ArrayCollection();
        $this->sensitiveDatas = new ArrayCollection();
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