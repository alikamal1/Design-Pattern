<?php

namespace Behavioral\Observer;

/**
 * Observer Design Pattern
 * lets you define subscription mechanism to notify multiple objects about any events that happen to object they're observing
 */

class User
{
    public $attribures = [];
    public function update($data): void
    {
        $this->attribures = array_merge($this->attribures, $data);
    }
}

class UserRepository implements \SplObserver
{
    private $user = [];
    private $observers = [];

    public function __construct()
    {
        $this->observers["*"] = [];
    }

    public function initEventGroup(string $event = "*"): void
    {
        if(!isset($this->observers[$event])) {
            $this->observers[$event] = [];
        }
    }

    public function getEventObservers(string $event = "*"): array
    {
        $this->initEventGroup($event);
        $group = $this->observers[$event];
        $all = $this->observers["*"];
        return array_merge($group, $all);
    }

    public function attach(\SplObserver $observer, string $event = "*"): void
    {
        $this->initEventGroup($event);
        $this->observers[$event][] = $observer;
    }

    public function detach(\SplObserver $observer, string $event = "*"): void
    {
        foreach($this->getEventObservers($event) as $key => $s) {
            if($s === $observer) {
                unset($this->observers[$event][$key]);
            }
        }
    }

    public function notify(string $event = "*", $data = null): void
    {
        echo "UserRepository: Broadcasting the '$event' event";
        foreach($this->getEventObservers($event) as $observer) {
            $observer->update($this, $event, $data);
        }
    }

    public function initialize($filename): void
    {
        echo "UserRepository: Loading user records from file";
        $this->notify("users:init", $filename);
    }

    public function createUser(array $data): User
    {
        echo "UserRepository: Creating user";
        $user = new User;
        $user->update($data);
        $id = bin2hex(openssl_random_pseudo_bytes(16));
        $user->update(["id" => $id]);
        $this->users[$id] = $user;
        $this->notify("users:created", $user);
        return $user;
    }

    public function updateUser(User $user, array $data): User
    {
        echo "UserRepository: Updating user";
        $id = $user->attribures["id"];
        if(!isset($this->user[$id])) {
            return null;
        }
        $user = $this->users[$id];
        $user->update($data);

        $this->notify("users:updated", $user);
        return $user;
    }

    public function deleteUser(User $user): void
    {
        echo "UserRepository: Deleting user";
        $id = $user->attribures["id"];
        if(!isset($this->users[$id])) {
            return;
        }

        unset($this->users[$id]);
        $this->notify("users:deleted", $user);
    }
}

class Logger implements \SplObserver
{
    private $filename;
    public function __construct($filename)
    {
        $this->filename = $filename;
        if(file_exists($this->filename)) {
            unlink($this->filename);
        }
    }
    public function update(\SplSubject $repository, string $event = null, $data = null): void
    {
        $entry = date("Y-m-d H:i:s") . ":'$event' with data " . json_encode($data);
        file_put_contents($this->filename, $entry, FILE_APPEND);
        echo "Logger: I've written '$event' entry to the log";
    }
}

class OnBoardingNotification implements \SplObserver
{
    private $adminEmail;
    public function __construct($adminEmail)
    {
        $this->adminEmail = $adminEmail;
    }
    public function update(\SplSubject $repository, string $event = null, $data = null): void
    {
        mail($this->adminEmail, "onBoarding required", "we have a new user here's his info: " . json_encode($data));
    }
}

$repository = new UserRepository;
$repository->attach(new Logger(__DIR__ . "/log.txt"), "*");
$repository->attach(new OnBoardingNotification("example@example.com"), "users:created");
$repository->initialize(__DIR__ . "/users.csv");

$user = $repository->createUser([
    "name" => "Ali Kamal",
    "email" => "example@example.com",
]);

$repository->deleteUser($user);

/*
UserRepository: Loading user records from a file.
UserRepository: Broadcasting the 'users:init' event.
Logger: I've written 'users:init' entry to the log.
UserRepository: Creating a user.
UserRepository: Broadcasting the 'users:created' event.
OnboardingNotification: The notification has been emailed!
Logger: I've written 'users:created' entry to the log.
UserRepository: Deleting a user.
UserRepository: Broadcasting the 'users:deleted' event.
Logger: I've written 'users:deleted' entry to the log.

2018-06-04 14:59:48: 'users:init' with data '"users.csv"'
2018-06-04 14:59:48: 'users:created' with data '{"attributes":{"name":"John Smith","email":"john99@example.com","id":"75b7f717bae23472ee1665bf5bfd2425"}}'
2018-06-04 14:59:48: 'users:deleted' with data '{"attributes":{"name":"John Smith","email":"john99@example.com","id":"75b7f717bae23472ee1665bf5bfd2425"}}'

*/