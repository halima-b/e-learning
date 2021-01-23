<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 *
 */
class Student extends User
{


    
    /**
     * @ORM\OneToMany(targetEntity=WishList::class, mappedBy="student", orphanRemoval=true)
     */
    private $wishlist;

    

    /**
     * @ORM\OneToMany(targetEntity=Enrolement::class, mappedBy="student", orphanRemoval=true)
     */
    private $enrolements;

    public function __construct()
    {
        $this->wishlist = new ArrayCollection();
        $this->enrolements = new ArrayCollection();
    }

   

  

    /**
     * @return Collection|WishList[]
     */
    public function getWishlist(): Collection
    {
        return $this->wishlist;
    }

    public function addWishlist(WishList $wishlist): self
    {
        if (!$this->wishlist->contains($wishlist)) {
            $this->wishlist[] = $wishlist;
            $wishlist->setStudent($this);
        }

        return $this;
    }

    public function removeWishlist(WishList $wishlist): self
    {
        if ($this->wishlist->contains($wishlist)) {
            $this->wishlist->removeElement($wishlist);
            // set the owning side to null (unless already changed)
            if ($wishlist->getStudent() === $this) {
                $wishlist->setStudent(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|Enrolement[]
     */
    public function getEnrolements(): Collection
    {
        return $this->enrolements;
    }

    public function addEnrolement(Enrolement $enrolement): self
    {
        if (!$this->enrolements->contains($enrolement)) {
            $this->enrolements[] = $enrolement;
            $enrolement->setStudent($this);
        }

        return $this;
    }

    public function removeEnrolement(Enrolement $enrolement): self
    {
        if ($this->enrolements->contains($enrolement)) {
            $this->enrolements->removeElement($enrolement);
            // set the owning side to null (unless already changed)
            if ($enrolement->getStudent() === $this) {
                $enrolement->setStudent(null);
            }
        }

        return $this;
    }

  

   
}
