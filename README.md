# Event Service

A PSR-14 event dispatcher with autowiring support.

## Table of Contents

- [Getting started](#getting-started)
    - [Requirements](#requirements)
    - [Highlights](#highlights)
    - [Simple Example](#simple-example)
- [Documentation](#documentation)
    - [Listeners](#listeners)
        - [Create Listeners](#create-listeners)
        - [Defining And Add Listener](#defining-and-add-listener)
        - [Retrieve Listeners](#retrieve-listeners)
    - [Dispatcher](#dispatcher)
        - [Create Dispatcher](#create-dispatcher)
        - [Dispachting Events](#dispatching-events)
    - [Events](#events)
        - [Create Events](#create-events)
        - [Add Listeners](#add-listeners)
        - [Dispatch Events](#dispatch-events)
        - [Supporting Events](#supporting-events)
- [Credits](#credits)
___

# Getting started

Add the latest version of the event service running this command.

```
composer require tobento/service-event
```

## Requirements

- PHP 8.0 or greater

## Highlights

- Framework-agnostic, will work with any project
- Decoupled design
- Autowiring support

## Simple Example

Here is a simple example of how to use the Event service.

```php
use Tobento\Service\Event\Dispatcher;
use Tobento\Service\Event\Listeners;

class FooEvent {}

$listeners = new Listeners();

$listeners->add(function(FooEvent $event) {
    // do something
});

$dispatcher = new Dispatcher($listeners);

$event = $dispatcher->dispatch(new FooEvent());
```

**Using Events**

```php
use Tobento\Service\Event\Events;

class FooEvent {}

$events = new Events();

$events->listen(function(FooEvent $event) {
    // do something
});

$event = $events->dispatch(new FooEvent());
```

# Documentation

## Listeners

The listeners class uses reflection to scan listeners for its events.

### Create Listeners

```php
use Tobento\Service\Event\Listeners;
use Tobento\Service\Event\ListenersInterface;
use Tobento\Service\Event\CallableFactoryInterface;
use Tobento\Service\Event\ListenerEventsResolverInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
    
$listeners = new Listeners(
    callableFactory: null, // null|CallableFactoryInterface
    listenerEventsResolver: null, // null|ListenerEventsResolverInterface
);

var_dump($listeners instanceof ListenersInterface);
// bool(true)

var_dump($listeners instanceof ListenerProviderInterface);
// bool(true)
```

**Using Autowiring**

You might want to use autowiring for creating your listeners.

```php
use Tobento\Service\Event\Listeners;
use Tobento\Service\Event\AutowiringCallableFactory;
use Tobento\Service\Container\Container;

class FooListener
{
    public function foo(FooEvent $event): void
    {
        // do something
    }
}

// any PSR-11 container
$container = new Container();
    
$listeners = new Listeners(
    callableFactory: new AutowiringCallableFactory($container),
);

$listeners->add(FooListener::class);
```

### Defining And Add Listener

As the listeners class uses reflection to scan listeners for its events named **$event**, there is no need to define its event(s) when adding a listener. But you might do so if you have multiple events in your listener and want only to listen for the specific events.

**Class using invoke**

```php
class FooListener
{
    public function __invoke(FooEvent $event): void
    {
        // do something
    }
}

class FooBuildInListener
{
    public function __construct(protected int $number) {}
    
    public function __invoke(FooEvent $event): void
    {
        // do something
    }
}

$listeners->add(new FooListener());

// using autowiring:
$listeners->add(FooListener::class);

// using autowiring with build-in parameters:
$listeners->add([FooBuildInListener::class, ['number' => 5]]);
```

**Class defining multiple events to listen**

```php
class FooBarListener
{
    public function foo(FooEvent $event): void
    {
        // do something
    }
    
    public function bar(BarEvent $event): void
    {
        // do something
    }
    
    public function another(AnotherEvent $event): void
    {
        // do something
    }    
    
    public function fooAndBar(FooEvent|BarEvent $event): void
    {
        // do something
    }    
}

// only listen to foo and bar event: 
$listeners->add(new FooBarListener())
          ->event(FooEvent::class, BarEvent::class);
          
// using autowiring:
$listeners->add(FooBarListener::class)
          ->event(FooEvent::class, BarEvent::class);
```

**Using closure**

```php
$listeners->add(function(FooEvent $event) {
    // do something
});
```

**Prioritize**

You might prioritize listeners by the following way:

```php
$listeners->add(function(FooEvent $event) {
    // do something
})->priority(1500);

// gets called first as higher priority.
$listeners->add(function(FooEvent $event) {
    // do something
})->priority(2000);
```

**Add a custom listener**

You might add a custom listener by implementing the following interface:

```php
use Tobento\Service\Event\ListenerInterface;
use Tobento\Service\Event\CallableFactoryInterface;

interface ListenerInterface
{
    /**
     * Returns the listener.
     *    
     * @return mixed
     */
    public function getListener(): mixed;

    /**
     * Returns the listener events.
     *    
     * @return array<string, array<mixed>>
     */
    public function getListenerEvents(): array;
    
    /**
     * Returns the listeners for the specified event.
     *
     * @param object $event
     * @param CallableFactoryInterface $callableFactory
     * @return iterable<callable>
     *   An iterable (array, iterator, or generator) of callables. Each
     *   callable MUST be type-compatible with $event.
     */
    public function getForEvent(object $event, CallableFactoryInterface $callableFactory): iterable;
    
    /**
     * Returns the priority.
     *    
     * @return int
     */
    public function getPriority(): int;   
}

$listeners->addListener(new AnyCustomListener());
```

### Retrieve Listeners

```php
use Tobento\Service\Event\ListenerInterface;

foreach($listeners->all() as $listener) {
    var_dump($listener instanceof ListenerInterface);
    // bool(true)
}
```

## Dispatcher

### Create Dispatcher

```php
use Tobento\Service\Event\Dispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Container\ContainerInterface;
use Tobento\Service\Event\Listeners;

$listeners = new Listeners();

$dispatcher = new Dispatcher(
    listenerProvider: $listeners, // ListenerProviderInterface
    container: null, // null|ContainerInterface
);

var_dump($dispatcher instanceof EventDispatcherInterface);
// bool(true)
```

**Using Autowire**

You might set a container for autowiring events methods. This will break PSR-14 definition although.

```php
use Tobento\Service\Event\Dispatcher;
use Tobento\Service\Event\Listeners;
use Tobento\Service\Container\Container;

class FooEvent {}
class Bar {}

$listeners = new Listeners();

// adding more parameters after the $event.
$listeners->add(function(FooEvent $event, Bar $bar) {
    // do something
});

// Any PSR-11 container
$container = new Container();

$dispatcher = new Dispatcher(
    listenerProvider: $listeners,
    container: $container,
);

$dispatcher->dispatch(new FooEvent());
```

### Dispatching Events

```php
$dispatcher->dispatch(new AnyEvent());
```

## Events

### Create Events

```php
use Tobento\Service\Event\Events;
use Tobento\Service\Event\EventsInterface;
use Tobento\Service\Event\ListenersInterface;
use Tobento\Service\Event\DispatcherFactoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

$events = new Events(
    listeners: null, // null|ListenersInterface
    dispatcherFactory: null, // null|DispatcherFactoryInterface
);

var_dump($events instanceof EventsInterface);
// bool(true)

var_dump($events instanceof EventDispatcherInterface);
// bool(true)
```

**Using the events factory**

```php
use Tobento\Service\Event\EventsFactory;
use Tobento\Service\Event\EventsFactoryInterface;
use Tobento\Service\Event\ListenersInterface;
use Tobento\Service\Event\DispatcherFactoryInterface;
use Tobento\Service\Event\EventsInterface;

$eventsFactory = new EventsFactory();

var_dump($eventsFactory instanceof EventsFactoryInterface);
// bool(true)

$events = $eventsFactory->createEvents(
    listeners: null, // null|ListenersInterface
    dispatcherFactory: null, // null|DispatcherFactoryInterface
);

var_dump($events instanceof EventsInterface);
// bool(true)
```

**Using the autowiring events factory**

```php
use Tobento\Service\Event\AutowiringEventsFactory;
use Tobento\Service\Event\EventsFactoryInterface;
use Tobento\Service\Event\EventsInterface;
use Tobento\Service\Container\Container;

// Any PSR-11 container
$container = new Container();

$eventsFactory = new AutowiringEventsFactory(
    container: $container,
    withAutowiringDispatching: true,
);

var_dump($eventsFactory instanceof EventsFactoryInterface);
// bool(true)

$events = $eventsFactory->createEvents();

var_dump($events instanceof EventsInterface);
// bool(true)
```

### Add Listeners

**Using listen method**

```php
$events->listen(FooListener::class);

$events->listen(AnyListener::class)
       ->event(FooEvent::class)
       ->priority(2000);
```

**Using listeners method**

```php
use Tobento\Service\Event\ListenersInterface;

$listeners = $events->listeners();

var_dump($listeners instanceof ListenersInterface);
// bool(true)

$listeners->add(AnyListener::class);
```

For more detail see [Defining And Add Listener](#defining-and-add-listener)

### Dispatch Events

```php
$events->dispatch(new AnyEvent());
```

### Supporting Events

You might use the HasEvents trait with the EventsAware interface for any classes supporting events.

```php
use Tobento\Service\Event\EventsAware;
use Tobento\Service\Event\HasEvents;
use Tobento\Service\Event\EventsInterface;
use Tobento\Service\Event\Events;

class AnyService implements EventsAware
{
    use HasEvents;
    
    public function __construct(EventsInterface $events)
    {
        $this->events = $events;
    }
}

$service = new AnyService(new Events());

var_dump($service->events() instanceof EventsInterface);
// bool(true)
```

# Credits

- [Tobias Strub](https://www.tobento.ch)
- [All Contributors](../../contributors)