# Protect against accidental use of superglobals.

By default, PHP passes all sensitive user information around in superglobal variables, available for reading and modification in any code, including third party libraries. This directly violates a lot of the benefits of Object Oriented Programming, and can lead to unmaintainable code.

Assuming there are object oriented abstractions to the superglobals set up, this library can be used to replace all superglobals with objects that alert the developer of their protection and encapsulation, with an optional whitelist of superglobals to keep.

***

<a href="https://circleci.com/gh/PhpGt/ProtectedGlobal" target="_blank">
	<img src="https://img.shields.io/circleci/project/PhpGt/ProtectedGlobal/master.svg?style=flat-square" alt="Build status" />
</a>
<a href="https://scrutinizer-ci.com/g/PhpGt/ProtectedGlobal" target="_blank">
	<img src="https://img.shields.io/scrutinizer/g/PhpGt/ProtectedGlobal/master.svg?style=flat-square" alt="Code quality" />
</a>
<a href="https://scrutinizer-ci.com/g/PhpGt/ProtectedGlobal" target="_blank">
	<img src="https://img.shields.io/scrutinizer/coverage/g/PhpGt/ProtectedGlobal/master.svg?style=flat-square" alt="Code coverage" />
</a>
<a href="https://packagist.org/packages/PhpGt/ProtectedGlobal" target="_blank">
	<img src="https://img.shields.io/packagist/v/PhpGt/ProtectedGlobal.svg?style=flat-square" alt="Current version" />
</a>
<a href="http://www.php.gt/dom" target="_blank">
	<img src="https://img.shields.io/badge/docs-www.php.gt/protectedglobal-26a5e3.svg?style=flat-square" alt="PHP.Gt/ProtectedGlobal documentation" />
</a>

There are two functions on the static `Protection` class:

1. `removeGlobals` - pass in an array containing the global arrays you wish to empty. Take an optional whitelist of keys to keep.
2. `overrideInternals` - pass in all superglobal arrays to override with the `ProtectedGlobal` class.

## Example usage:

```php
// Before protecting, abstract the globals using an OOP mechanism of choice.
$input = new Input($_GET, $_POST, $_FILES);
// etc...

removeGlobals([$_ENV, $_SERVER, $_GET, $_POST, $_FILES, $_COOKIE, $_SESSION], ["get" => ["xdebug"]]);
overrideInternals($_GLOBALS, $_ENV, $_SERVER, $_GET, $_POST, $_FILES, $_COOKIE, $_SESSION);

// Now an exception will be thrown when trying to access a global variable:
$_SESSION["god-object"] = "Value I want to pass around globally";
```
