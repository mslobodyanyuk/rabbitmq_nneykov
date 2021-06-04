Laravel & RabbitMQ queues
=========================

* ***Actions on the deployment of the project:***

- Making a new project rabbitmq_nneykov.loc:
																	
sudo chmod -R 777 /var/www/LARAVEL/RabbitMQ/rabbitmq_nneykov.loc

//!!!! .conf
sudo cp /etc/apache2/sites-available/test.loc.conf /etc/apache2/sites-available/rabbitmq_nneykov.loc.conf
		
sudo nano /etc/apache2/sites-available/rabbitmq_nneykov.loc.conf

sudo a2ensite rabbitmq_nneykov.loc.conf

sudo systemctl restart apache2

sudo nano /etc/hosts

cd /var/www/LARAVEL/RabbitMQ/rabbitmq_nneykov.loc

- Deploy project:

	`git clone << >>`
	
	_+ Сut the contents of the folder up one level and delete the empty one._

	`composer install`		

---

Nevyan Neykov

[Laravel & RabbitMQ queues (5:27)]( https://www.youtube.com/watch?v=K-xzRM6EKHg&ab_channel=NevyanNeykov )

We will see how to use locally RabbitMQ together with Laravel to dispatch and handle messages. Quite useful when dealing with distributed applications.

- ALLOCATE MEMORY:

	`free -m`
	
	`sudo /bin/dd if=/dev/zero of=/var/swap.1 bs=1M count=1024`
	
	`sudo /sbin/mkswap /var/swap.1`
	
	`sudo /sbin/swapon /var/swap.1`

Error: 
	
_"proc_open(): fork failed - Cannot allocate memory"_	
	
<https://www.nicesnippets.com/blog/proc-open-fork-failed-cannot-allocate-memory-laravel-ubuntu>
						
	composer create-project laravel/laravel rabbitmq_nneykov.loc "^6"

_+ Сut the contents of the folder up one level and delete the empty one._

[(0:30)]( https://youtu.be/K-xzRM6EKHg?t=30 )

	composer require vladimir-yuldashev/laravel-queue-rabbitmq "^9"	

- ALLOCATE MEMORY:

	`free -m`
	
	`sudo /bin/dd if=/dev/zero of=/var/swap.1 bs=1M count=1024`
	
	`sudo /sbin/mkswap /var/swap.1`
	
	`sudo /sbin/swapon /var/swap.1`

Error: 
	
_"proc_open(): fork failed - Cannot allocate memory"_	
	
<https://www.nicesnippets.com/blog/proc-open-fork-failed-cannot-allocate-memory-laravel-ubuntu>

`composer.json + composer update`
	
	composer update

	cd /var/www/LARAVEL/RabbitMQ/rabbitmq_nneykov.loc

[(0:40)]( https://youtu.be/K-xzRM6EKHg?t=40 ) Add connection to `config/queue.php`:

<https://github.com/vyuldashev/laravel-queue-rabbitmq>

Create database `rabbitmq_nneykov`, Collation - `utf8mb4_general_ci`.

[(1:05)]( https://youtu.be/K-xzRM6EKHg?t=65 ) `.env`:

```
DB_DATABASE=rabbitmq_nneykov
DB_USERNAME=your_username
DB_PASSWORD=your_password
...
QUEUE_CONNECTION=rabbitmq
...

RABBITMQ_HOST=127.0.0.1
RABBITMQ_PORT=5672
RABBITMQ_USER='guest'
RABBITMQ_PASSWORD='guest'
RABBITMQ_VHOST='/'
```

	php artisan config:cache

	php artisan migrate

[(1:35)]( https://youtu.be/K-xzRM6EKHg?t=95 )

	cd /var/www/LARAVEL/RabbitMQ/rabbitmq_nneykov.loc

	php artisan make:job TestJob

`app/Jobs/TestJob.php`:

```php
private $data;
	 
public function __construct($data)
{
	$this->data = $data;
}

/**
 * Execute the job.
 *
 * @return void
 */
public function handle()
{
	print_r($this->data);
	
	echo 'event has been handled.';
}
```

[(2:15)]( https://youtu.be/K-xzRM6EKHg?t=135 ) `app/Providers/EventServiceProvider.php`:

```php
use App\Jobs\TestJob;
...
public function boot()
{
	$this->app->bind(
		TestJob::class. '@handle', //whenever ask for testjob@handle (instance of class and event) return the callback function
		fn($job)=> $job->handle());            		
}
```

	php artisan make:controller RegisterController
	
Error:
 
![screenshot of sample]( https://github.com/mslobodyanyuk/rabbitmq_nneykov/blob/master/public/images/1.png )

<https://laracasts.com/discuss/channels/laravel/php-arrow-functions-just-stopped-working>

	php artisan make:model User

```php
use App\Jobs\TestJob;
...
public function boot()
{
	$this->app->bind(
		TestJob::class. '@handle', //whenever ask for testjob@handle (instance of class and event) return the callback function
		//fn($job)=> $job->handle());            
		function($job){ return $job->handle; });  
}
```

`routes/web`:
	
```php	
Route::get('/', [RegisterController::class,'index']);
```	

[(2:45)]( https://youtu.be/K-xzRM6EKHg?t=165 ) `app/Http/Controllers/RegisterController.php`:

```php 
use App\Jobs\TestJob;
use App\Models\User;
...
public function index()
{
		//factory(User::class, 5)->create();
	$users=User::all();
	print_r($users->toJson());
	TestJob::dispatch($users->toArray()); //pass to the job handle
}
```

[(3:00)]( https://youtu.be/K-xzRM6EKHg?t=180 )

	php artisan serve 
	
`In Browser`:

	127.0.0.1:8000

OR
	
	rabbitmq_nneykov.loc	

[(3:10)]( https://youtu.be/K-xzRM6EKHg?t=190 ) Array of objects:

![screenshot of sample]( https://github.com/mslobodyanyuk/rabbitmq_nneykov/blob/master/public/images/2.png )

---

* ***Install RabbitMQ***

<https://coderun.ru/blog/kak-ustanovit-rabbitmq-server-v-ubuntu-18-04-i-16-04-lts/>

	echo 'deb http://www.rabbitmq.com/debian/ testing main' | sudo tee /etc/apt/sources.list.d/rabbitmq.list
	wget -O- https://www.rabbitmq.com/rabbitmq-release-signing-key.asc | sudo apt-key add -

	sudo apt-get update
	sudo apt-get install rabbitmq-server

	sudo systemctl enable rabbitmq-server
	sudo systemctl start rabbitmq-server
	sudo systemctl stop rabbitmq-server

	sudo systemctl status rabbitmq-server

![screenshot of sample]( https://github.com/mslobodyanyuk/rabbitmq_nneykov/blob/master/public/images/3.png )

	sudo rabbitmq-plugins enable rabbitmq_management

![screenshot of sample]( https://github.com/mslobodyanyuk/rabbitmq_nneykov/blob/master/public/images/4.png )

---

[(3:25)]( https://youtu.be/K-xzRM6EKHg?t=205 )

`In New Terminal`:

	cd /var/www/LARAVEL/RabbitMQ/rabbitmq_nneykov.loc

	php artisan queue:work
		
Error: 

_Undefined index: exchange_
		
<https://www.larablocks.com/package/vladimir-yuldashev/laravel-queue-rabbitmq> - Add to the `config/queue.php`.

![screenshot of sample]( https://github.com/mslobodyanyuk/rabbitmq_nneykov/blob/master/public/images/5.png )	

`app/Http/Controllers/RegisterController.php`:

```php 
use App\Jobs\TestJob;
use App\Models\User;
...
public function index()
{
	TestJob::dispatch('hello ');
}
```

![screenshot of sample]( https://github.com/mslobodyanyuk/rabbitmq_nneykov/blob/master/public/images/6.png )

[(4:15)]( https://youtu.be/K-xzRM6EKHg?t=255 )

	sudo rabbitmq-plugins enable rabbitmq_management

[(4:40)]( https://youtu.be/K-xzRM6EKHg?t=280 ) `In Browser`:

	127.0.0.1:15672	
	
OR

	http://rabbitmq_nneykov.loc:15672/
		
	guest	
	guest
	
[(4:55)]( https://youtu.be/K-xzRM6EKHg?t=295 ) Sending message by just refreshing Laravel root site( F5 ).

[(5:05)]( https://youtu.be/K-xzRM6EKHg?t=305 ) We see certain messages that have been queued. And we see that we received one message and this is the rate of the message.

![screenshot of sample]( https://github.com/mslobodyanyuk/rabbitmq_nneykov/blob/master/public/images/7.png )

#### Useful links:

Nevyan Neykov

[Laravel & RabbitMQ queues (5:27)]( https://www.youtube.com/watch?v=K-xzRM6EKHg&ab_channel=NevyanNeykov )

Vladimir Yuldashev

<https://github.com/vyuldashev/laravel-queue-rabbitmq>

<https://www.larablocks.com/package/vladimir-yuldashev/laravel-queue-rabbitmq>

Install RabbitMQ

<https://coderun.ru/blog/kak-ustanovit-rabbitmq-server-v-ubuntu-18-04-i-16-04-lts/>

[Install RabbitMQ on Ubuntu and work with NodeJS]( https://www.youtube.com/watch?v=FmAMhpeek8A&ab_channel=NevyanNeykov )

Possible Errors

<https://www.nicesnippets.com/blog/proc-open-fork-failed-cannot-allocate-memory-laravel-ubuntu>

<https://laracasts.com/discuss/channels/laravel/php-arrow-functions-just-stopped-working>