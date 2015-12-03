<?php
namespace Behigorri\Entities;

use Doctrine\ORM\Mapping AS ORM;

/**
*
* @ORM\Entity
* @ORM\Table(
*   name="Tag",
*   options={
*     "collate"="utf8_general_ci", "charset"="utf8"
*   }
* )
*/
class Tag
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

    // ...
    /**
    * @ORM\ManyToMany(targetEntity="SensitiveData", mappedBy="tags")
    */
    private $sensitiveDatas;

    public function __construct() {
        $this->sensitiveDatas = new \Doctrine\Common\Collections\ArrayCollection();
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