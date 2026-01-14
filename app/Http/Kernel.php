protected $routeMiddleware = [
    // ... existing middleware
    'role' => \App\Http\Middleware\CheckRole::class,
    'time.register.check' => \App\Http\Middleware\CheckTimeRegisterCompletion::class,
    'can' => \Illuminate\Auth\Middleware\Authorize::class,
];