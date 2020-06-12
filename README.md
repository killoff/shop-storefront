### Standalone usage

symfony server:start --document-root=www

r
### Include in Magento
```
use Drinks\Storefront\App;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

(new App())->run(Request::createFromGlobals(), new Response());
```
