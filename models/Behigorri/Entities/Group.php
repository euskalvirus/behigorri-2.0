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
    private $password;

    /**
     * @var string
     * @ORM\Column(type="string", length=355, nullable=true)
     */
    private $publicKey;

    /**
     * @var string
     * @ORM\Column(type="string", length=355, nullable=true)
     */
    private $privateKey;

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
     * Set password
     *
     * @param string $password
     *
     * @return Group
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
     * Set publicKey
     *
     * @param string $publicKey
     *
     * @return Group
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey= $publicKey;

        return $this;
    }

    /**
     * Get publicKey
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Set privateKey
     *
     * @param string $privateKey
     *
     * @return Group
     */
    public function setPrivateKey($publicKey)
    {
        $this->privateKey= $privateKey;

        return $this;
    }

    /**
     * Get privateKey
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }


}
