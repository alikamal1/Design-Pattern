<?php

namespace Structural\Adapter;

/**
 * Adapter Design Pattern
 * provide a unified interface that allows objects with incompatible interfaces to collaborate
 */

 interface Notification
 {
     public function send(string $title, string $message);
 }

 class EmailNotification implements Notification
 {
     private $adminEmail;

     public function __construct(string $adminEmail)
     {
         $this->adminEmail = $adminEmail;
     }

     public function send(string $title, string $message): void
     {
         mail($this->admin, $title, $message);
         echo "Sent email with title '$title' to '{$this->adminEmail}' that says '$message'";
     }
 }

 class SlackNotification implements Notification
 {
     private $slack;
     private $chatId;

     public function __construct(SlackApi $slack, string $chatId)
     {
         $this->slack = $slack;
         $this->chatId = $chatId;
     }

     public function send(string $title, string $message): void
     {
         $slackMessage = "#" . $title . "#" . strip_tags($message);
         $this->slack->login();
         $this->slack->sendMessage($this->chatId, $slackMessage);
     }
 }

class SlackApi
{
    private $login;
    private $apiKey;

    public function __construct(string $login, string $apiKey)
    {
        $this->login = $login;
        $this->apiKey = $apiKey;
    }

    public function logIn()
    {
        echo "Logged in to slack '{$this->login}'";
    }

    public function sendMessage(string $chatId, string $message): void
    {
        echo "Posted following message into the '$chatId' chat: '$message'";
    }
    
}

function clientCode(Notification $notification)
 {
     echo $notification->send("Website is down", "our website is not responding call admins");
 }

 $notification = new EmailNotification("developer@example.com");
 clientCode($notification);

 $slackApi = new SlackApi("example.com", "xxxxxxx");
 $notification = new SlackNotification($slackApi, "Example.com Developers");
 clientCode($notification);

 /*
Client code is designed correctly and works with email notifications:
Sent email with title 'Website is down!' to 'developers@example.com' that says '<strong style='color:red;font-size: 50px;'>Alert!</strong> Our website is not responding. Call admins and bring it up!'.

The same client code can work with other classes via adapter:
Logged in to a slack account 'example.com'.
Posted following message into the 'Example.com Developers' chat: '#Website is down!# Alert! Our website is not responding. Call admins and bring it up!'.

 */