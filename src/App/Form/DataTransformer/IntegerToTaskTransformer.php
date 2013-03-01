<?php
namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Task;

class IntegerToTaskTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     *
     * @param  Task|null $issue
     * @return string
     */
    public function transform($task)
    {
        if (null === $task) {
            return "";
        }

        return $task->getId();
    }

    /**
     * Transforms a number to an object (job).
     *
     * @param  string $number
     * @return Job|null
     * @throws TransformationFailedException if object (Job) is not found.
     */
    public function reverseTransform($number)
    {
        if (!$number) {
            return null;
        }

        $issue = $this->om
            ->getRepository('App:Task')
            ->find($number)
        ;

        if (null === $issue) {
            throw new TransformationFailedException(sprintf(
                'An task with number "%s" does not exist!',
                $number
            ));
        }

        return $issue;
    }
}