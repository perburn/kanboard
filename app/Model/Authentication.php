<?php

namespace Kanboard\Model;

use SimpleValidator\Validator;
use SimpleValidator\Validators;
use Gregwar\Captcha\CaptchaBuilder;

/**
 * Authentication model
 *
 * @package  model
 * @author   Frederic Guillot
 */
class Authentication extends Base
{
    /**
     * Validate user login form
     *
     * @access public
     * @param  array   $values           Form values
     * @return array   $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validateForm(array $values)
    {
        list($result, $errors) = $this->validateFormCredentials($values);

        if ($result) {
            if (! $this->authenticationManager->passwordAuthentication($values['username'], $values['password'])) {
                $result = false;
                $errors['login'] = t('Bad username or password');
            }
        }

        return array($result, $errors);
    }

    /**
     * Validate credentials syntax
     *
     * @access public
     * @param  array   $values           Form values
     * @return array   $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validateFormCredentials(array $values)
    {
        $v = new Validator($values, array(
            new Validators\Required('username', t('The username is required')),
            new Validators\MaxLength('username', t('The maximum length is %d characters', 50), 50),
            new Validators\Required('password', t('The password is required')),
        ));

        return array(
            $v->execute(),
            $v->getErrors(),
        );
    }

    /**
     * Validate captcha
     *
     * @access public
     * @param  array   $values           Form values
     * @return boolean
     */
    public function validateFormCaptcha(array $values)
    {
        if ($this->hasCaptcha($values['username'])) {
            if (! isset($this->sessionStorage->captcha)) {
                return false;
            }

            $builder = new CaptchaBuilder;
            $builder->setPhrase($this->sessionStorage->captcha);
            return $builder->testPhrase(isset($values['captcha']) ? $values['captcha'] : '');
        }

        return true;
    }
}
