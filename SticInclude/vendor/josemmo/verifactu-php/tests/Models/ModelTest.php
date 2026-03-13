<?php
namespace josemmo\Verifactu\Tests\Models;

use josemmo\Verifactu\Exceptions\InvalidModelException;
use josemmo\Verifactu\Models\Model;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints as Assert;

class SampleModel extends Model {
    #[Assert\NotBlank]
    #[Assert\Length(exactly: 4)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $quantity;
}

final class ModelTest extends TestCase {
    #[DoesNotPerformAssertions]
    public function testNotThrowsOnValidModel(): void {
        $model = new SampleModel();
        $model->name = 'abcd';
        $model->quantity = 2;
        $model->validate();
    }

    public function testThrowsOnInvalidModel(): void {
        $this->expectException(InvalidModelException::class);
        $model = new SampleModel();
        $model->name = 'This is not a valid name';
        $model->quantity = 0;
        $model->validate();
    }
}
