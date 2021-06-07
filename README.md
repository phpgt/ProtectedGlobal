# Protect against accidental use of superglobals.

By default, PHP passes all sensitive user information around in superglobal variables, available for reading and modification in any code, including third party libraries. This directly violates a lot of the benefits of Object Oriented Programming, and can lead to unmaintainable code.

Assuming there are object oriented abstractions to the superglobals set up, this library can be used to replace all superglobals with objects that alert the developer of their protection and encapsulation, with an optional whitelist of superglobals to keep.

***

<a href="https://github.com/PhpGt/ProtectedGlobal/actions" target="_blank">
	<img src="https://badge.status.php.gt/protectedglobal-build.svg" alt="Build status" />
</a>
<a href="https://scrutinizer-ci.com/g/PhpGt/ProtectedGlobal" target="_blank">
	<img src="https://badge.status.php.gt/protectedglobal-quality.svg" alt="Code quality" />
</a>
<a href="https://scrutinizer-ci.com/g/PhpGt/ProtectedGlobal" target="_blank">
	<img src="https://badge.status.php.gt/protectedglobal-coverage.svg" alt="Code coverage" />
</a>
<a href="https://packagist.org/packages/PhpGt/ProtectedGlobal" target="_blank">
	<img src="https://badge.status.php.gt/protectedglobal-version.svg" alt="Current version" />
</a>
<a href="http://www.php.gt/protectedglobal" target="_blank">
	<img src="https://badge.status.php.gt/protectedglobal-docs.svg" alt="PHP.Gt/ProtectedGlobal documentation" />
</a>

There are two functions on the static `Protection` class:

1. `removeGlobals` - pass in an array containing the global arrays you wish to empty. Take an optional whitelist of keys to keep.
2. `overrideInternals` - pass in all superglobal arrays to override with the `ProtectedGlobal` class.

## Example usage:

```php
// Before protecting, abstract the globals using an OOP mechanism of choice.
$input = new Input($_GET, $_POST, $_FILES);
// etc...

Protection::removeGlobals([$_ENV, $_SERVER, $_GET, $_POST, $_FILES, $_COOKIE, $_SESSION], ["get" => ["xdebug"]]);
Protection::overrideInternals($_GLOBALS, $_ENV, $_SERVER, $_GET, $_POST, $_FILES, $_COOKIE, $_SESSION);

// Now an exception will be thrown when trying to access a global variable:
$_SESSION["god-object"] = "Value I want to pass around globally";
```
