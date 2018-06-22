<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb combination of simplicity, elegance, and innovation give you tools you need to build any application with which you are tasked.

## Learning Laravel

Laravel has the most extensive and thorough documentation and video tutorial library of any modern web application framework. The [Laravel documentation](https://laravel.com/docs) is thorough, complete, and makes it a breeze to get started learning the framework.

If you're not in the mood to read, [Laracasts](https://laracasts.com) contains over 900 video tutorials on a range of topics including Laravel, modern PHP, unit testing, JavaScript, and more. Boost the skill level of yourself and your entire team by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for helping fund on-going Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](http://patreon.com/taylorotwell):

- **[Vehikl](http://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[British Software Development](https://www.britishsoftware.co)**
- **[Styde](https://styde.net)**
- [Fragrantica](https://www.fragrantica.com)
- [SOFTonSOFA](https://softonsofa.com/)

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

## Json Web Token with Laravel 
## jwt install
composer require tymon/jwt-auth

- Add service provider in app.php
```php
'providers' => [

    ...

    Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
]
```
- Add aliases

'JWTAuth' => Tymon\JWTAuth\Facades\JWTAuth::class

## laravel

- run php artisan make:auth
- run php artisan make:model Client -m
```php
public function up()
{
    Schema::create('clients', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name');
        $table->text('address');
        $table->string('telephone');
        $table->timestamps();
    });
}
```
- run php artisan migrate
- ClientFactory.php
```php
$factory->define(App\Client::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'address' => $faker->address,
        'telephone' => $faker->phoneNumber
    ];
});
```
- seeds/DatabaseSeeder.php
factory(App\Client::class, 50)->create();
- run php artisan db:seed

## Setting Up JWT
- run php artisan make:controller FrontEndUserController
- in 
```php
public function signUp(Request $request)
{
    $user = User::create(['email' => $request->email, 'password' => bcrypt($request->password)]);
}
```
- In our routes/api.php
Route::post('/signup', 'FrontEndUserController@signUp');
Route::post('/signin', 'FrontEndUserController@signIn');

-test with Postman
 [project_path]/api/signup

- in FrontEndUserController
```php
public function signIn(Request $request)
{
    try {
        if (! $token = JWTAuth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
    } catch (JWTException $e) {
        return response()->json(['error' => 'could_not_create_token'], 500);
    }

    return response()->json(compact('token'));
}
```
## Calling The API
- run php artisan make:controller ClientController
In ClientController
```php
public function index()
{
    return Client::all();
}
```
- in api.php
```php
Route::group(['middleware' => 'jwt.auth'], function() {
    Route::get('/clients', 'ClientController@index');
});
```
- in app/Http/Kernel.php
```php
'jwt.auth' => \Tymon\JWTAuth\Middleware\GetUserFromToken::class
```
To remedy this, pass an authorization header, with the key of Authorization and value of Bearer [token] (copy the token you created earlier). Now run the GET call again and you should get the list of clients back as JSON. (NB. If you get a token_expired error just call the previous POST endpoint and get a new token.)


- to change expire of jwt
tymon-> jwt-auth->src->config->config.php (ttl  )
http://unlikenesses.com/2017-08-10-jwts-with-react-and-laravel/
http://jwt-auth.readthedocs.io/en/develop/quick-start/