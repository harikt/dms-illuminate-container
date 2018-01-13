<?php

namespace Dms\Ioc\Tests;

use Dms\Core\Exception\InvalidArgumentException;
use Dms\Ioc\IlluminateContainer;
use Illuminate\Container\Container;
use Psr\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;

class IlluminateContainerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return ContainerInterface
     */
    private function createContainer(array $map = [])
    {
        $container = new Container();
        foreach ($map as $key => $value) {
            $container->bind($key, $value);
        }

        return new IlluminateContainer($container);
    }

    public function provideFalseData()
    {
        return [
            'Named but does not exist in the container.' =>
                [$this->createContainer(), 'some.id'],

            'Interface exists but is itself not instantiable and not bound to a concrete class.' =>
                [$this->createContainer(), ContainerInterface::class],
        ];
    }

    public function provideTrueData()
    {
        return [
            'Named and bound to a concrete class.' =>
                [
                    $this->createContainer(['some.id' => self::class]),
                    'some.id',
                    self::class
                ],
            'Not instantiable but bound to a concrete class.' =>
                [
                    $this->createContainer([ContainerInterface::class => self::class]),
                    ContainerInterface::class,
                    self::class
                ],
            'Has not been bound but is an instantiable concrete class.' =>
                [
                    $this->createContainer(),
                    self::class,
                    self::class
                ],
        ];
    }

    /**
     * @param ContainerInterface $container
     * @param string $id
     * @dataProvider provideFalseData
     */
    public function testFalse(ContainerInterface $container, $id)
    {
        $this->assertFalse($container->has($id));
        $this->assertFalse($container->has($id));
    }

    /**
     * @param ContainerInterface $container
     * @param string $id
     * @param string $expectedType
     * @dataProvider provideTrueData
     */
    public function testTrue(ContainerInterface $container, $id, $expectedType)
    {
        $this->assertTrue($container->has($id));
        $this->assertTrue($container->has($id));
        $this->assertInstanceOf($expectedType, $container->get($id));
    }
   

    public function testGetIlluminateContainer()
    {
        $container = $this->createContainer();
        $this->assertInstanceOf(Container::class, $container->getIlluminateContainer());
    }

    public function testHasReturnTrue()
    {
        $container = $this->createContainer();
        $this->assertTrue($container->has(Example::class));
    }

    public function testHasReturnsFalse()
    {
        $container = $this->createContainer();
        $this->assertFalse($container->has(NoClass::class));
    }

    public function testGetReturnsObject()
    {
        $container = $this->createContainer();
        $this->assertInstanceOf(Example::class, $container->get(Example::class));
    }

    public function testMakeWith()
    {
        $container = $this->createContainer();
        $object = $container->makeWith(Example::class, ['foo' => 'Hello']);
        $this->assertEquals('Hello', $object->getFoo());
    }

    public function testBindAndAlias()
    {
        $container = $this->createContainer();
        $container->bind(IlluminateContainer::SCOPE_SINGLETON, Example::class, Example::class);
        $container->alias(Example::class, 'example');
        $this->assertSame($container->get('example'), $container->get(Example::class));
    }

    public function testUniqueInstancePerGet()
    {
        $container = $this->createContainer();
        $container->bind(IlluminateContainer::SCOPE_INSTANCE_PER_RESOLVE, Example::class, Example::class);
        $this->assertNotSame($container->get(Example::class), $container->get(Example::class));
    }

    public function testBindCallback()
    {
        $container = $this->createContainer();

        // SCOPE_SINGLETON
        $container->bindCallback(IlluminateContainer::SCOPE_SINGLETON, 'first', function () {
            return new Example('callback1');
        });
        $first = $container->get('first');
        $this->assertInstanceOf(Example::class, $first);
        $this->assertSame($first, $container->get('first'));
        $this->assertEquals('callback1', $first->getFoo());

        // SCOPE_INSTANCE_PER_RESOLVE
        $container->bindCallback(IlluminateContainer::SCOPE_INSTANCE_PER_RESOLVE, 'second', function () {
            return new Example('callback2');
        });
        $second = $container->get('second');
        $this->assertInstanceOf(Example::class, $second);
        $this->assertNotSame($second, $container->get('second'));
        $this->assertEquals('callback2', $second->getFoo());
    }

    public function testBindValue()
    {
        $container = $this->createContainer();

        $container->bindValue('hello', new Example('World'));
        $hello = $container->get('hello');
        $this->assertInstanceOf(Example::class, $hello);
        $this->assertEquals('World', $hello->getFoo());
    }

    public function testInvalidArgumentException()
    {
        $container = $this->createContainer();
        $this->expectException(InvalidArgumentException::class);
        $container->bindCallback('Invalid', 'second', function () {
            return new Example('callback2');
        });
    }

    // public function testGetThrowsContainerException()
    // {
    //     $container = $this->createContainer();
    //     $this->assertInstanceOf(ContainerExceptionInterface::class, $container->get(NoClass::class));
    // }
}
