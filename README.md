Common
======

[![Build Status](https://api.travis-ci.org/andytruong/event.svg?branch=v0.1)](https://travis-ci.org/andytruong/event) [![Latest Stable Version](https://poser.pugx.org/andytruong/event/v/stable.png)](https://packagist.org/packages/andytruong/event) [![Dependency Status](https://www.versioneye.com/php/andytruong:event/2.3.0/badge.svg)](https://www.versioneye.com/php/andytruong:event/2.3.0) [![License](https://poser.pugx.org/andytruong/event/license.png)](https://packagist.org/packages/andytruong/event)

Wrapper for Symfony Event Dispatcher to provide more functionality.

```php
<?php
use Symfony\Component\EventDispatcher\Event;
use AndTruong\Common\EventAware;

class MyClass extends EventAware
{

    public function myEventAwareMethod()
    {
        $this->dispatch('my.event.before');
        $event = new Event();
        $this->dispatch('my.event.after', $event);

        // or simpler
        $this->trigger('my.other.event', $this, ['param 1', 'param 2']);
    }

}

// Class usage
$myobj = new MyClass();
$myobj->getDispatcher()->addListener('my.event.before', function(\AndyTruong\Common\Event $e) {
    $e->getTarget(); // instance of MyClass
    $e->getParams(); // ['param 1', 'param 2']
});
$myobj->myEventAwareMethod();
```
