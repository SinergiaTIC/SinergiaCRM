<?php
/**
 * Weak maps allow creating a map from objects to arbitrary values
 * (similar to SplObjectStorage) without preventing the objects that are used
 * as keys from being garbage collected. If an object key is garbage collected,
 * it will simply be removed from the map.
 *
 * @since 8.0
 * @source https://github.com/JetBrains/phpstorm-stubs/blob/master/Core/Core_c.php
 *
 * @template TKey of object
 * @template TValue
 * @template-implements IteratorAggregate<TKey, TValue>
 */
final class WeakMap implements ArrayAccess, Countable, IteratorAggregate {
    /**
     * Returns {@see true} if the value for the object is contained in
     * the {@see WeakMap} and {@see false} instead.
     *
     * @param TKey $object Any object
     * @return bool
     */
    public function offsetExists($object): bool {}

    /**
     * Returns the existsing value by an object.
     *
     * @param TKey $object Any object
     * @return TValue Value associated with the key object
     */
    public function offsetGet($object): mixed {}

    /**
     * Sets a new value for an object.
     *
     * @param TKey $object Any object
     * @param TValue $value Any value
     * @return void
     */
    public function offsetSet($object, mixed $value): void {}

    /**
     * Force removes an object value from the {@see WeakMap} instance.
     *
     * @param TKey $object Any object
     * @return void
     */
    public function offsetUnset($object): void {}

    /**
     * Returns an iterator in the "[object => mixed]" format.
     *
     * @return Traversable<TKey, TValue>
     */
    public function getIterator(): Iterator {}

    /**
     * Returns the number of items in the {@see WeakMap} instance.
     *
     * @return int
     */
    public function count(): int {}
}
