<?php

namespace Creational\Factory_Method;

/**
 * Factory Method Design Pattern
 * provide an interface for creating objects in superclass but allow subclass to alter the type of objects that will be created
 * 
 * before $p = new FacebookConnector;
 * after  $p = $this->getSocialNetwork();
 * 
 * this allows changing the type of the product being created by socialNetworkPoster subclasses
 * 
 */

interface SocialNetworkConnector
{
    public function logIn(): void;
    public function createPost($content): void;
    public function logOut(): void;
}

class FacebookConnector implements SocialNetworkConnector
{
    private $login, $password;

    public function __construct(string $login, string $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    public function logIn(): void
    {
        echo "Send HTTP API request to log in user $this->login with passowrd $this->password";
    }

    public function createPost($content): void
    {
        echo "Send HTTP API request to create  a post in Facebook timeline";
    }

    public function logout(): void
    {
        echo "Send HTTP API request to log out user $this->login";
    }
}

class TwitterConnector implements SocialNetworkConnector
{
    private $email, $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function logIn(): void
    {
        echo "Send HTTP API request to log in user $this->email with passowrd $this->password";
    }

    public function createPost($content): void
    {
        echo "Send HTTP API request to create  a post in Facebook timeline";
    }

    public function logout(): void
    {
        echo "Send HTTP API request to log out user $this->email";
    }
}

abstract class SocialNetworkPoster
{
    abstract public function getSocialNetwork(): SocialNetworkConnector;

    public function post($content): void
    {
        $network = $this->getSocialNetwork();
        $network->logIn();
        $network->createPost($content);
        $network->logout();
    }
}

class FacebookPoster extends SocialNetworkPoster
{
    private $login, $password;

    public function __construct(string $login, string $password) {
        $this->login = $login;
        $this->password = $password;
    }

    public function getSocialNetwork(): SocialNetworkConnector
    {
        return new FacebookConnector($this->login, $this->password);
    }
}

class TwitterPoster extends SocialNetworkPoster
{
    private $email, $password;

    public function __construct(string $email, string $password) {
        $this->email = $email;
        $this->password = $password;
    }

    public function getSocialNetwork(): SocialNetworkConnector
    {
        return new TwitterConnector($this->email, $this->password);
    }
}

function clientCode(SocialNetworkPoster $creator)
{
    $creator->post("Hello World");
    $creator->post("Hello World 2");
}


echo "Testing Concrete Creator 1";
clientCode(new FacebookPoster("Ali Kamal", "*******"));

echo "Testing Concrete Creator 2";
clientCode(new TwitterPoster("AliKamal@email.com", "*******"));

/*
Testing ConcreteCreator1:
Send HTTP API request to log in user john_smith with password ******
Send HTTP API requests to create a post in Facebook timeline.
Send HTTP API request to log out user john_smith
Send HTTP API request to log in user john_smith with password ******
Send HTTP API requests to create a post in Facebook timeline.
Send HTTP API request to log out user john_smith


Testing ConcreteCreator2:
Send HTTP API request to log in user john_smith@example.com with password ******
Send HTTP API requests to create a post in LinkedIn timeline.
Send HTTP API request to log out user john_smith@example.com
Send HTTP API request to log in user john_smith@example.com with password ******
Send HTTP API requests to create a post in LinkedIn timeline.
Send HTTP API request to log out user john_smith@example.com
*/