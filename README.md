# stripelaravel
stripe payment gateway integration in laravel 5.4

### Development Setup

1. Clone the repository using git clone https://github.com/rajdipchauhan/stripelaravel.git. By default the master branch will be downloaded into a new directory called greenzebra.

2. Run the setup commands, listed below
```
$ git clone https://github.com/rajdipchauhan/stripelaravel.git
$ cd stripelaravel
$ composer install
```
### DB Setup
find .env.example in root folder and rename file with .env file
```
$ php artisan key:generate
```
Use following command to execute code:
```
$ php artisan serve
```
define your STRIPE_API_KEY in .env file
