<?php
namespace Behigorri\Entities;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Illuminate\Database\Eloquent\Model;
use Behigorri\Traits\TimestampCreation;

/**
 * @ORM\Entity(repositoryClass="Behigorri\Repositories\BehigorriRepository")
 * @ORM\Table(
 *   name="SensitiveData",
 *   options={
*       "collate"="utf8_bin", "charset"="utf8"
*    }
 * )
 *
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
     * @ORM\JoinColumn(name="ownerId", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="sensitiveDatas")
     * @ORM\JoinTable(name="SensitiveDataTag")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="sensitiveDatas")
     * @ORM\JoinColumn(name="groupId", referencedColumnName="id", nullable=true)
     */
    private $group;

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
    private $hasFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $fileName;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $fileExtension;

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
     * Set group
     *
     * @param \Behigorri\Entities\Group $group
     *
     * @return Group
     */
    public function setGroup(\Behigorri\Entities\Group $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \Behigorri\Entities\Group
     */
    public function getGroup()
    {
        return $this->group;
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
     * Set hasFile
     *
     * @param boolean $hasFile
     *
     * @return User
     */
    public function setHasFile($hasFile)
    {
    	$this->hasFile = $hasFile;

    	return $this;
    }

    /**
     * Get hasFile
     *
     * @return boolean
     */
    public function getHasFile()
    {
    	//var_dump((bool)$this->userActive);exit;
    	return $this->hasFile;
    }

    public function containsTag($tagName)
    {
    	foreach ($this->getTags() as $tag)
    	{
    		if($tag->getName() == $tagName)
    		{
    			return true;
    		}
    	}
    	return false;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     *
     * @return SensitiveData
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set fileExtension
     *
     * @param string $fileExtension
     *
     * @return SensitiveData
     */
    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;

        return $this;
    }

    /**
     * Get fileExtension
     *
     * @return string
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }
}
