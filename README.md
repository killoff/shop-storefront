### Standalone usage

symfony server:start --document-root=www


### Include in Magento
```
use Drinks\Storefront\App;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

define('STOREFRONT_DIR', dirname(__DIR__));
(new App())->run(Request::createFromGlobals(), new Response());
```
