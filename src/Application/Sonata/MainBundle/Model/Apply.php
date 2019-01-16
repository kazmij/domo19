<?php

namespace Application\Sonata\MainBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;


class Apply
{

    /**
     * @Assert\NotBlank()
     * @var string
     */
    protected $fullName;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @var string
     */
    protected $email;

    /**
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $subject;

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $city;

    /**
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $rule1;

    /**
     * @var string
     */
    protected $rule2;

    /**
     *
     * @var string
     */
    protected $rule3;

    /**
     * @Recaptcha\IsTrue
     */
    public $recaptcha;

    /**
     * @return mixed
     */
    public function getRecaptcha()
    {
        return $this->recaptcha;
    }

    /**
     * @param mixed $recaptcha
     */
    public function setRecaptcha($recaptcha)
    {
        $this->recaptcha = $recaptcha;
    }

    /**
     * @return string
     */
    public function getRule1()
    {
        return $this->rule1;
    }

    /**
     * @param string $rule1
     */
    public function setRule1($rule1)
    {
        $this->rule1 = $rule1;
    }

    /**
     * @return string
     */
    public function getRule2()
    {
        return $this->rule2;
    }

    /**
     * @param string $rule2
     */
    public function setRule2($rule2)
    {
        $this->rule2 = $rule2;
    }

    /**
     * @return string
     */
    public function getRule3()
    {
        return $this->rule3;
    }

    /**
     * @param string $rule3
     */
    public function setRule3($rule3)
    {
        $this->rule3 = $rule3;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $phone;

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param mixed $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * @Assert\NotBlank(message="Please, upload the CV.")
     * @Assert\File(
     *     mimeTypes={ "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/vnd.oasis.opendocument.text"},
     *     maxSize = "5M"
     * )
     */
    private $file;

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

}