<?php
namespace Behigorri\Entities;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Illuminate\Database\Eloquent\Model;
use Behigorri\Traits\TimestampCreation;

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
    use TimestampCreation;

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
    
    /**
     * @var boolean
     * @ORM\Column(type="boolean", options = {"default":0}, nullable=false)
     */
    private $isFile;

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
     * @return SensitiveData
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
     * @return SensitiveData
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
     * @return SensitiveData
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
     * Set user
     *
     * @param \Behigorri\Entities\User $user
     *
     * @return SensitiveData
     */
    public function setUser(\Behigorri\Entities\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Behigorri\Entities\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add tag
     *
     * @param \Behigorri\Entities\Tag $tag
     *
     * @return SensitiveData
     */
    public function addTag(\Behigorri\Entities\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \Behigorri\Entities\Tag $tag
     */
    public function removeTag(\Behigorri\Entities\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add group
     *
     * @param \Behigorri\Entities\Group $group
     *
     * @return SensitiveData
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
    
    /**
     * Set userActive
     *
     * @param boolean $isFile
     *
     * @return User
     */
    public function setIsFile($isFile)
    {
    	$this->isFile = $isFile;
    
    	return $this;
    }
    
    /**
     * Get userActive
     *
     * @return boolean
     */
    public function getIsFile()
    {
    	//var_dump((bool)$this->userActive);exit;
    	return $this->isFile;
    }
}
