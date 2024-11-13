<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Yaml\Yaml;
use App\Models\User;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    use RefreshDatabase;

    private const ROUTES_PARAMS_FILE = __DIR__ . '/../Fixtures/routes_params.yaml';

    // Routes that should not be tested
    private const ROUTES_NOT_TESTED = [
        'verification.verify',
        'login',
        'login_post'
    ];

    // Terms to ignore when testing routes
    private const ROUTES_TERMS_TO_IGNORE = [
        'debugbar',
        'sanctum',
        '_',
        'register',
        'password',
    ];

    /**
     * Test all routes based on the parameters defined in the YAML file.
     */
    public function testAllRoutes(): void
    {
        // Load route parameters from the YAML file
        $data = Yaml::parseFile(self::ROUTES_PARAMS_FILE);

        if (!isset($data['parameters']['route_params'])) {
            $this->fail('Invalid YAML structure: Missing route_params');
        }

        $routeParams = $data['parameters']['route_params'];

        // Get all routes
        $routes = Route::getRoutes();

        // Create a user for authentication tests
        $user = User::factory()->create();

        // Loop through each route and verify the response
        foreach ($routes as $route) {
            // Ensure $route is an instance of Illuminate\Routing\Route
            if (!($route instanceof \Illuminate\Routing\Route)) {
                continue;
            }

            // Retrieve the route name and URI
            $routeName = $route->getName();
            $routeUri = $route->uri();

            // Skip routes that are specified to be excluded or contain ignored terms
            if (in_array($routeName, self::ROUTES_NOT_TESTED) ||
                $this->containsAny($routeUri, self::ROUTES_TERMS_TO_IGNORE)) {
                continue;
            }

            // Log and display the current route being tested
            Log::info("Testing route: " . $routeUri . ' - ' . $routeName);
            echo "✓ Testing route: " . $routeUri . " (name: " . $routeName . ")\n";

            // Extract parameters, expected status code, authentication requirement, and HTTP method
            $params = $routeParams[$routeName]['params'] ?? [];
            $expectedStatusCode = $routeParams[$routeName]['response-status-code'] ?? 200;
            $needUserAuth = $routeParams[$routeName]['need-user-auth'] ?? false;
            $method = $routeParams[$routeName]['method'] ?? 'GET';

            // Authenticate the user if required, otherwise, skip authentication middleware
            if ($needUserAuth) {
                $this->actingAs($user);
            } else {
                $this->withoutMiddleware(\Illuminate\Auth\Middleware\Authenticate::class);
            }

            // Perform the request based on the specified HTTP method
            switch (strtoupper($method)) {
                case 'POST':
                    $response = $this->post(route($routeName, $params), $params);
                    break;
                case 'PUT':
                    $response = $this->put(route($routeName, $params), $params);
                    break;
                case 'DELETE':
                    $response = $this->delete(route($routeName, $params));
                    break;
                default:
                    $response = $this->get(route($routeName, $params));
                    break;
            }

            // Assert the response status code
            $response->assertStatus($expectedStatusCode);
        }
    }

    /**
     * Check if a string contains any of the specified terms.
     *
     * @param string $string
     * @param array $terms
     * @return bool
     */
    private function containsAny(string $string, array $terms): bool
    {
        foreach ($terms as $term) {
            if (strpos($string, $term) !== false) {
                return true;
            }
        }
        return false;
    }
}
