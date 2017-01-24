<?php
namespace Behigorri\Entities;

use Doctrine\ORM\Mapping AS ORM;

/**
*
* @ORM\Entity
* @ORM\Table(
*   name="Tag",
*   options={
*     "collate"="utf8_bin", "charset"="utf8"
*   }
* )
*@ORM\Entity(repositoryClass="Behigorri\Repositories\BehigorriRepository")
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
     * @return Tag
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
     * Set createdAt
     *
     * @param string $createdAt
     *
     * @return Tag
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
     * @return Tag
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
     * @return Tag
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
}
