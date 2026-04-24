# Protect against accidental use of superglobals.

By default, PHP passes all sensitive user information around in superglobal variables, available for reading and modification in any code, including third party libraries. This directly violates a lot of the benefits of Object Oriented Programming, and can lead to unmaintainable code.

Assuming there are object oriented abstractions to the superglobals set up, this library can be used to replace all superglobals with objects that alert the developer of their protection and encapsulation, with an optional whitelist of superglobals to keep.

***

<a href="https://github.com/PhpGt/ProtectedGlobal/actions" target="_blank">
	<img src="https://badge.status.php.gt/protectedglobal-build.svg" alt="Build status" />
</a>
<a href="https://app.codacy.com/gh/PhpGt/ProtectedGlobal" target="_blank">
	<img src="https://badge.status.php.gt/protectedglobal-quality.svg" alt="Code quality" />
</a>
<a href="https://app.codecov.io/gh/PhpGt/ProtectedGlobal" target="_blank">
	<img src="https://badge.status.php.gt/protectedglobal-coverage.svg" alt="Code coverage" />
</a>
<a href="https://packagist.org/packages/PhpGt/ProtectedGlobal" target="_blank">
	<img src="https://badge.status.php.gt/protectedglobal-version.svg" alt="Current version" />
</a>
<a href="http://www.php.gt/protectedglobal" target="_blank">
	<img src="https://badge.status.php.gt/protectedglobal-docs.svg" alt="PHP.Gt/ProtectedGlobal documentation" />
</a>

There are two main methods on the `Protection` class:

1. `removeGlobals` - pass in an array of superglobal data, along with an optional whitelist of offsets to preserve.
2. `overrideInternals` - replace the internal superglobals with `ProtectedGlobal` wrappers containing the whitelisted data.

## Example usage:

```php
use GT\ProtectedGlobal\Protection;

$protection = new Protection();

// Before protecting, abstract the globals using an OOP mechanism of choice.
$input = new Input($_GET, $_POST, $_FILES);
// etc...

$whitelist = $protection->removeGlobals(
	[
		"_ENV" => $_ENV,
		"_SERVER" => $_SERVER,
		"_GET" => $_GET,
		"_POST" => $_POST,
		"_FILES" => $_FILES,
		"_COOKIE" => $_COOKIE,
		"_SESSION" => $_SESSION ?? [],
	],
	[
		"_COOKIE" => ["XDEBUG_SESSION"],
	]
);

$protection->overrideInternals($whitelist);

// Now an exception will be thrown when trying to access a global variable:
$_SESSION["god-object"] = "Value I want to pass around globally";
```

# Proudly sponsored by

[JetBrains Open Source sponsorship program](https://www.jetbrains.com/community/opensource/)

[![JetBrains logo.](https://resources.jetbrains.com/storage/products/company/brand/logos/jetbrains.svg)](https://www.jetbrains.com/community/opensource/)
