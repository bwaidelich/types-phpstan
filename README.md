Custom [PHPStan](https://phpstan.org/) rules and extensions for the [wwwision/types](https://github.com/bwaidelich/types/) package

## What's included

- **Rules** that enforce the [best practices](https://github.com/bwaidelich/types/#best-practices) for
  `#[TypeBased]` classes (must be `final`, `readonly`, have a private constructor and never be
  constructed directly).
- A **reflection extension** for dynamic schemas: instances of `DynamicRecord` are read via `__get`,
  so PHPStan would normally report `$record->someProperty` as access to an undefined property. The
  extension teaches PHPStan that any property of a `DynamicRecord` is a readable (immutable) `mixed`
  value, so object-accessor syntax type-checks. (A dynamic record's shape is only known at runtime,
  so per-property types cannot be inferred.)

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
