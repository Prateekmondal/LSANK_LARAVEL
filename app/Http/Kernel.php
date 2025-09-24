protected $routeMiddleware = [
    // ... existing middleware
    'role' => \App\Http\Middleware\CheckRole::class,
    'can' => \Illuminate\Auth\Middleware\Authorize::class,
];