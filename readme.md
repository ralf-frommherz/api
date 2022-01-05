# Api Bundle
## Installation
1. `composer req rfrommherz/api`
2. **config/bundles.php**: Add new bundle to bundles.php

```php
Rf\ApiBundle\JwtBundle::class => ['dev' => true],
```

## Usage
### ApiDto
ApiDto's can be used as parameters in your controllers. To enable the support for this, your class must extend ApiDto.
Each ApiDto is passed to the symfony validator, which enables the support for validation annotations.

```php
use Rf\ApiBundle\Dto\ApiDto;
use Symfony\Component\Validator\Constraints as Assert;

class Fetch extends ApiDto
{
    /**
     * @Assert\NotBlank
     * @Assert\Url
     * @var string
     */
    private string $repositoryUrl;
```

Arguments will be automatically resolved and can be passed as controller arguments.
```php
public function index(Fetch $fetch): Response
{
    ...
}
```

````shell
curl --location --request GET 'http://localhost/fetch' \
--header 'Content-Type: application/json' \
--data-raw '{
    "repositoryUrl": "http://...",
}'
````



