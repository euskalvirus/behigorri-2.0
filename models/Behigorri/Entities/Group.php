<?php
namespace Behigorri\Entities;

use Doctrine\ORM\Mapping AS ORM;
use Behigorri\Traits\TimestampCreation as TimestampCreation;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(
 *   name="`Group`",
 *   options={
*      "collate"="utf8_bin", "charset"="utf8"
*   })
*@ORM\Entity(repositoryClass="Behigorri\Repositories\BehigorriRepository")
 */
class Group
{
    use TimestampCreation;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, length=255, nullable=false)
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
     * @ORM\OneToMany(targetEntity="SensitiveData", mappedBy="group")
     */
    private $sensitiveDatas;

    /**
     * @ORM\ManyToMany(targetEntity="SensitiveData", mappedBy="groups")
     */
    /*private $sensitiveDatas;*/

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

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @var string
     */
    private $decryptPassword;

    /**
     * @var string
     * @ORM\Column(type="binary", length=32)
     */
    private $salt;

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
     * @return Group
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
     * Set description
     *
     * @param string $description
     *
     * @return Group
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param string $createdAt
     *
     * @return Group
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
     * @return Group
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
     * Add user
     *
     * @param \Behigorri\Entities\User $user
     *
     * @return Group
     */
    public function addUser(\Behigorri\Entities\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Behigorri\Entities\User $user
     */
    public function removeUser(\Behigorri\Entities\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add sensitiveData
     *
     * @param \Behigorri\Entities\SensitiveData $sensitiveData
     *
     * @return Group
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
     * Set decryptPassword
     *
     * @param string $decryptPassword
     *
     * @return Group
     */
    public function setDecryptPassword($decryptPassword)
    {
        $this->decryptPassword = $decryptPassword;

        return $this;
    }

    /**
     * Get decryptPassword
     *
     * @return string
     */
    public function getDecryptPassword()
    {
        return $this->decryptPassword;
    }

    /**
     * Set salt
     *
     * @param binary $salt
     *
     * @return Group
     */
    public function setSalt($salt)
    {
        $this->salt= $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return binary
     */
    public function getSalt()
    {
      if($this->salt!==null && strlen(stream_get_contents($this->salt,32))==32)
      {
        fseek($this->salt,0);
        $salt = stream_get_contents($this->salt,32);
        fseek($this->salt,0);
        return $salt;
      }
    }



}
