<?php

namespace Creational\Factory_Method;

abstract class SocialNetworkPoster
{
    abstract public function getSocialNetwork(): SocialNetworkConnector;

    public function post($content): void
    {
        $network = $this->getSocialNetwork();
        $network->login();
        $network->createPost($content);
        $network->logout();
    }
}

class FacebookPoster extends SocialNetworkPoster
{
    private $login, $password;

    public function __construct(string $login, string $password)
    {
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
    private $login, $password;

    public function __construct(string $login, string $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    public function getSocialNetwork(): SocialNetworkConnector
    {
        return new TwitterConnector($this->login, $this->password);
    }
}

interface SocialNetworkConnector
{
    public function logIn(): void;
    public function logOut(): void;
    public function createPost($content): void;
}

class FacebookConnector implements SocialNetworkConnector
{
    private $login, $password;

    public function __construct(sting $login, string $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    public function logIn(): void
    {
        echo "Send HTTP API request to login in user $this->login with password $this->password \n";
    }

    public function logOut(): void
    {
        echo "Send HTTP API request to logout user in user $this->login \n";
    }

    public function createPost($content): void
    {
        echo "Send HTTP API request to create post in Facebook \n";
    }
}

class TwitterConnector implements SocialNetworkConnector
{
    private $login, $password;

    public function __construct(sting $login, string $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    public function logIn(): void
    {
        echo "Send HTTP API request to login in user $this->login with password $this->password \n";
    }

    public function logOut(): void
    {
        echo "Send HTTP API request to logout user in user $this->login \n";
    }
    
    public function createPost($content): void
    {
        echo "Send HTTP API request to create post in Twitter \n";
    }
}

function clientCode(SocialNetworkPoster $creator)
{
    $creator->post("Hello World!");
    $creator->post("a New Post");
}

echo "Testing CncreteCreateor 1:\n";
clientCode(new FacebookPoster("Ali Kamal", "******"));
echo "\n\n";

echo "Testing CncreteCreateor 2:\n";
clientCode(new TwitterPoster("Ali Kamal", "******"));
echo "\n\n";

?>