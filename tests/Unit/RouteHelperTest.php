<?php

namespace Tests\Unit;

use App\Helpers\RouteHelper;
use Illuminate\Support\Facades\File;
use InvalidArgumentException;
use RuntimeException;
use Tests\TestCase;

class RouteHelperTest extends TestCase
{
    private string $routesTestPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->routesTestPath = base_path('routes/route-helper-test');

        File::ensureDirectoryExists($this->routesTestPath . '/nested/deep');
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->routesTestPath);

        parent::tearDown();
    }

    public function test_import_route_file_accepts_filename_without_or_with_php_extension(): void
    {
        File::put($this->routesTestPath . '/single-route.php', "<?php\n\nreturn ['loaded' => true];\n");

        $this->assertSame(['loaded' => true], RouteHelper::importRouteFile('single-route', 'route-helper-test'));
        $this->assertSame(['loaded' => true], RouteHelper::importRouteFile('single-route.php', 'route-helper-test'));
    }

    public function test_import_route_file_rejects_ambiguous_or_invalid_extensions(): void
    {
        $this->expectException(InvalidArgumentException::class);

        RouteHelper::importRouteFile('single-route.php.php', 'route-helper-test');
    }

    public function test_import_route_file_rejects_other_extensions(): void
    {
        $this->expectException(InvalidArgumentException::class);

        RouteHelper::importRouteFile('single-route.txt', 'route-helper-test');
    }

    public function test_import_routes_from_folder_imports_direct_php_files_and_respects_except(): void
    {
        $GLOBALS['route_helper_loaded'] = [];

        File::put($this->routesTestPath . '/nested/alpha.php', "<?php\n\n\$GLOBALS['route_helper_loaded'][] = 'alpha';\n");
        File::put($this->routesTestPath . '/nested/beta.php', "<?php\n\n\$GLOBALS['route_helper_loaded'][] = 'beta';\n");
        File::put($this->routesTestPath . '/nested/ignored.php', "<?php\n\n\$GLOBALS['route_helper_loaded'][] = 'ignored';\n");
        File::put($this->routesTestPath . '/nested/deep/gamma.php', "<?php\n\n\$GLOBALS['route_helper_loaded'][] = 'gamma';\n");

        RouteHelper::importRoutesFromFolder('route-helper-test', 'nested', 'ignored');

        $this->assertSame(['alpha', 'beta'], $GLOBALS['route_helper_loaded']);

        unset($GLOBALS['route_helper_loaded']);
    }

    public function test_import_routes_from_folder_accepts_nested_subfolders_as_array_or_path_string(): void
    {
        $GLOBALS['route_helper_loaded'] = [];

        File::put($this->routesTestPath . '/nested/deep/gamma.php', "<?php\n\n\$GLOBALS['route_helper_loaded'][] = 'gamma';\n");

        RouteHelper::importRoutesFromFolder('route-helper-test', ['nested', 'deep']);
        RouteHelper::importRoutesFromFolder('route-helper-test', 'nested/deep');

        $this->assertSame(['gamma', 'gamma'], $GLOBALS['route_helper_loaded']);

        unset($GLOBALS['route_helper_loaded']);
    }

    public function test_route_imports_reject_path_traversal(): void
    {
        $this->expectException(InvalidArgumentException::class);

        RouteHelper::importRoutesFromFolder('../routes');
    }

    public function test_missing_route_folder_throws_runtime_exception(): void
    {
        $this->expectException(RuntimeException::class);

        RouteHelper::importRoutesFromFolder('route-helper-test', 'missing');
    }
}
