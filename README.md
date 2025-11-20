Custom [PHPStan](https://phpstan.org/) rules for the [wwwision/types](https://github.com/bwaidelich/types/) package

## Installation

To use this extension, require it in [Composer](https://getcomposer.org/):

```
composer require --dev wwwision/types-phpstan
```

If you also install [phpstan/extension-installer](https://github.com/phpstan/extension-installer) then you're all set!

<details>
  <summary>Manual installation</summary>

If you don't want to use `phpstan/extension-installer`, include rules.neon in your project's PHPStan config:

```
includes:
    - vendor/wwwision/types-phpstan/rules.neon
```

</details>
