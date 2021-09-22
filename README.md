# 基于 PSR-15 的简易 PHP 框架
### 1. 安装
```bash
composer create-project vivid-lamp/pipe-skeleton
```
### 2. 说明
本框架在入口文件处将 4 个基本中间件加入中间件队列，调用 run 方法会执行中间件。
```php
(function () {
    require_once __DIR__ . '/../vendor/autoload.php';

    $app = new App(__DIR__ . '/../');

    $app->pipe(ExceptionHandlerMiddleware::class);

    $app->pipe(InitializeMiddleware::class);

    $app->pipe(RouteMiddleware::class);

    $app->pipe(RouteMissedMiddleware::class);

    $app->run();
})();
```
你可以加入任何自己需要的中间件，也可以修改框架自带的中间件。如果你愿意，可以修改框架中的任何代码。
### 3. 特点
简单，直观。可以清楚地知道框架的生命周期，并根据自己的需要轻松地调整它。
