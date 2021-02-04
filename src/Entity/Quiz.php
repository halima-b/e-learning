<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Normalizer\JsonSerializable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuizRepository::class)
 */
class Quiz  
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @ORM\OneToOne(targetEntity=Course::class, mappedBy="quiz", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id", nullable=true)
     */
    private $course;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="quiz")
     */
    private $question;

    public function __construct()
    {
        $this->question = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        // set (or unset) the owning side of the relation if necessary
        $newQuiz = null === $course ? null : $this;
        if ($course->getQuiz() !== $newQuiz) {
            $course->setQuiz($newQuiz);
        }

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestion(): Collection
    {
        return $this->question;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->question->contains($question)) {
            $this->question[] = $question;
            $question->setQuiz($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->question->contains($question)) {
            $this->question->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getQuiz() === $this) {
                $question->setQuiz(null);
            }
        }

        return $this;
    }

    public function to_json() {
        return json_encode(array(
            'question' => $this->getQuestion(),
                          
        ));
    }
}
