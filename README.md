## Note

In production it may worth to use [Lumen](https://lumen.laravel.com/) (lighted, fast version of Laravel designed specificly for microservices), but for this task I prefered normal Laravel with handy artisan commands and other helper tools.

During implementation, I convinced that Laravel and Symfony have a lot in common.

## Start

Run RabbitMQ with default parameters (localhost, 5672, 'guest/guest') or specify them in .env file. Also in .env it is needed to specify that we want to use rabbitmq (as in .env.example):

```QUEUE_CONNECTION=rabbitmq```

Install packages:

```composer i```

Run app:

```php artisan serve```

To dispatch sample message into the queue open laravel homepage. Most likely: [http://localhost:8000/](http://localhost:8000/)

## Task 1

> Program the skeleton of a small microservice that will listen on a queue (e.g ActiveMQ, RabbitMQ or ZeroMQ).

I chose RabbitMQ, because this is the tool I used before. Also it seems it is the most popular one, so it has big community and many great articles.

> The microservice should provide a CLI-command that will start the listening process

I may think about writing [custom console commands](https://laravel.com/docs/8.x/artisan#writing-commands), but Laravel has built-in command for starting listening process:

```php artisan queue:work```

> To avoid a memory overflow, the process should only run for a certain period of time. The time should be configurable when starting the script.

```php artisan queue:work --max-time=600```

Here --max-time is number of seconds when the listening process will keep alive. We also may want to specify maximum number of jobs:

```php artisan queue:work --max-jobs=1000```

I found that in Symfony we can specify memory limit: 

```php bin/console messenger:consume async --time-limit=3600 --memory-limit=128M```

> Please describe with few words, how would you ensure that this command is always executed without a manual start/stop?

There is a special tool which will take care of that - [Supervisor](https://laravel.com/docs/8.x/queues#supervisor-configuration). It works both for Laravel and Symfony queues. 

## Task 2

> Imagine the queue message contains information about another message that can be sent via different channels such a SMS, Email or WhatsApp.
> Please define a default class structure/interface for this kind of message.

I created Laravel Models for that: [Message](https://github.com/Doszhan/LaravelConsumerSample/blob/main/app/Models/Message.php) and [MessageAttachment](https://github.com/Doszhan/LaravelConsumerSample/blob/main/app/Models/MessageAttachment.php).

The key points:

- Message can be of SMS, Email or Whatsapp types. Therefore we have $type attribute;
- We can store types of messages in small table in database. But since it is going to be modified very rarely we can define it in Model;
- There are helper methods in Models to deal with $type;
- Email has a Subject, therefore we have $title attribute;
- Email and Whatsapp messages may have $attachments.

> Please describe with few words, how would you implement the sending of a message for different channels.

By default we send messages to `default` channel. We can configure default channel by setting `RABBITMQ_QUEUE` in .env file.

To send to different channels we use following syntax:

```MessageJob::dispatch('hello')->onQueue('high');```

We can set priority channels by parameters when running listener:

```php artisan queue:work --queue=high,default```

## Test

Since it is a microservice there is a great straitforward way of testing. In test we can send a message into the queue and check if the microservice did what it should do.