<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // @H: Magic function to export variables to javascript
        Blade::directive('jsvar', function ($var) {

            // This directive should print something like this:
            // var jsvar = JSON.parse('{!! json_encode($var,JSON_HEX_APOS) !!}');

            // Json encode
            $json = "json_encode($var,JSON_HEX_APOS|JSON_HEX_QUOT)";
            $json = "str_replace(\"\\\\\",\"\\\\\\\\\"," . $json . ')';

            // JS output
            $echo = "<?php echo \"JSON.parse('\" . $json .  \"')\"; ?>";

            return $echo;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
