stringOperations.class.php
======

Credits
--------

This class is made by unreal4u (Camilo Sperberg). [http://unreal4u.com/](unreal4u.com)

About this class
--------

* Originally conceived to be a receiver of all loose string-related functions that I've made throughout the years
* Later also a playground to learn about multibyte capabilities of strings

Detailed description
---------

This package is a collection of functions related to string manipulation.

Basic usage
----------

<pre>include('stringOperations.class.php');
$stringOperations = new stringOperations();
$result = $stringOperations->truncate('this is a bigger text', 15);
</pre>

* Congratulations! Result will have a truncated string, compatible with UTF-8 characters:
 <pre>
 this is a bigger...
 </pre>
* Please see examples and PHPUnit tests for more options and advanced usage

Pending
---------
* Search for more loose functions spattered around my codebase

Version History
----------

* 0.1:
    * Created class

Contact the author
-------

* Twitter: [@unreal4u](http://twitter.com/unreal4u)
* Website: [http://unreal4u.com/](http://unreal4u.com/)
* Github:  [http://www.github.com/unreal4u](http://www.github.com/unreal4u)
