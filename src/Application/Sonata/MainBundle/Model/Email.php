<?php

namespace Application\Sonata\MainBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;


class Email {

    public static $subjects = [
        'Sprzedaj' => 'Sprzedaj',
        'Kup' => 'Kup'
    ];

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
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $body;

    /**
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $rule1;

    /**
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $rule2;

    /**
     *
     * @var string
     */
    protected $rule3;

//    /**
//     * @Recaptcha\IsTrue
//     */
    public $recaptcha;

    /**
     * @return string
     */
    public function getSendTo()
    {
        return $this->sendTo;
    }

    /**
     * @param string $sendTo
     */
    public function setSendTo($sendTo)
    {
        $this->sendTo = $sendTo;
    }

    /**
     * @var string
     */
    protected $sendTo;

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
    public function getRule4()
    {
        return $this->rule4;
    }

    /**
     * @param string $rule4
     */
    public function setRule4($rule4)
    {
        $this->rule4 = $rule4;
    }

    /**
     * @var string
     */
    protected $rule4;

    /**
     * @return string
     */
    public function getRule5()
    {
        return $this->rule5;
    }

    /**
     * @param string $rule4
     */
    public function setRule5($rule5)
    {
        $this->rule5 = $rule5;
    }

    /**
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $rule5;


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
     *
     * @var string
     */
    protected $status;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

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
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
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


}