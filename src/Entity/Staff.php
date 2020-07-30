<?php
namespace App\Entity;
use App\Repository\StaffRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 *
 * @ORM\Entity(repositoryClass=StaffRepository::class)
 * @UniqueEntity( fields={"username"},
 *                message="Identifiant déjà utilisé"
 *                )
 * @UniqueEntity( fields={"email"},
 *                message="E-mail déjà utilisé"
 *                )
 */
class Staff implements UserInterface{

	/**
	 *
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 *
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
	 */
	private $first_name;

	/**
	 *
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
	 */
	private $last_name;

	/**
	 *
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
	 */
	private $username;

	/**
	 *
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
	 */
	private $password;

	/**
	 *
	 * @ORM\Column(type="string", length=255)
	 * @Assert\Email( message = "Format d'e-mail invalide : '{{ value }}'"
	 *                )
	 */
	private $email;

	/**
	 *
	 * @ORM\Column(type="string", length=1024, nullable=true)
	 */
	private $address1;

	/**
	 *
	 * @ORM\Column(type="string", length=1024, nullable=true)
	 */
	private $address2;

	/**
	 *
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $zipcode;

	/**
	 *
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $city;

	/**
	 *
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $phone;

	/**
	 *
	 * @ORM\ManyToMany(targetEntity=Role::class, inversedBy="staffs")
	 */
	private $roleCollection;

	public function __construct(){
		$this->roleCollection = new ArrayCollection();
	}

	public function getId(): ?int{
		return $this->id;
	}

	public function getFirstName(): ?string{
		return $this->first_name;
	}

	public function setFirstName(string $first_name): self{
		$this->first_name = $first_name;

		return $this;
	}

	public function getLastName(): ?string{
		return $this->last_name;
	}

	public function setLastName(string $last_name): self{
		$this->last_name = $last_name;

		return $this;
	}

	public function getUsername(): ?string{
		return $this->username;
	}

	public function setUsername(string $username): self{
		$this->username = $username;

		return $this;
	}

	public function getPassword(): ?string{
		return $this->password;
	}

	public function setPassword(string $password): self{
		$this->password = $password;

		return $this;
	}

	public function getEmail(): ?string{
		return $this->email;
	}

	public function setEmail(string $email): self{
		$this->email = $email;

		return $this;
	}

	public function getAddress1(): ?string{
		return $this->address1;
	}

	public function setAddress1(string $address1): self{
		$this->address1 = $address1;

		return $this;
	}

	public function getAddress2(): ?string{
		return $this->address2;
	}

	public function setAddress2(?string $address2): self{
		$this->address2 = $address2;

		return $this;
	}

	public function getZipcode(): ?string{
		return $this->zipcode;
	}

	public function setZipcode(string $zipcode): self{
		$this->zipcode = $zipcode;

		return $this;
	}

	public function getCity(): ?string{
		return $this->city;
	}

	public function setCity(string $city): self{
		$this->city = $city;

		return $this;
	}

	public function getPhone(): ?string{
		return $this->phone;
	}

	public function setPhone(?string $phone): self{
		$this->phone = $phone;

		return $this;
	}

	/**
	 *
	 * @return Collection|Role[]
	 */
	public function getRoleCollection(): Collection{
		return $this->roleCollection;
	}

	public function addRoleCollection(Role $role): self{
		if (!$this->roleCollection->contains($role)){
			$this->roleCollection[] = $role;
		}

		return $this;
	}

	public function removeRoleCollection(Role $role): self{
		if ($this->roleCollection->contains($role)){
			$this->roleCollection->removeElement($role);
		}

		return $this;
	}

	public function __toString(){
		return $this->username;
	}

	/**
	 *
	 * @todo ?
	 * {@inheritdoc}
	 * @see \Symfony\Component\Security\Core\User\UserInterface::eraseCredentials()
	 */
	public function eraseCredentials(){}

	/**
	 * Empty
	 *
	 * {@inheritdoc}
	 * @see \Symfony\Component\Security\Core\User\UserInterface::getSalt()
	 */
	public function getSalt(){
		return '';
	}

	/**
	 * La liste des roles est basée sur la table "role" de la base de données et donc de l'entité Role qui a une relation ManyToMany avec cette entité Staff.
	 * De ce fait, on retourne la liste des roles en faisant une boucle sur getRoleCollection.
	 * Par défaut, un membre du Staff aura à minima le role "ROLE_USER"
	 *
	 * {@inheritdoc}
	 * @see \Symfony\Component\Security\Core\User\UserInterface::getRoles()
	 */
	public function getRoles(): array{
		$roles = [
			'ROLE_USER'
		];
		foreach ($this->getRoleCollection() as $Role){
			$roles[] = $Role->getName();
		}
		return array_unique($roles);
	}

	/**
	 * En principe, on ne devrait pas non plus "setter" les roles par ce biais, on doit d'abord add ou remove un role de la collection.
	 * Puis lorsqu'on appelle la fonction getRoles(), celui-ci renverra une liste basée sur la collection roleCollection
	 *
	 * @deprecated
	 * @param array $roles
	 * @return \App\Entity\Staff
	 */
	private function setRoles(array $roles){
		$this->roles = $roles;
		return $this;
	}
}
