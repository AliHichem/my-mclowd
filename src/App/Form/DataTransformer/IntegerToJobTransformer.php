<?php
namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Job;

class IntegerToJobTransformer implements DataTransformerInterface
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
     * @param  Job|null $issue
     * @return string
     */
    public function transform($job)
    {
        if (null === $job) {
            return "";
        }

        return $issue->getNumber();
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
            ->getRepository('App:Job')
            ->find($number)
        ;

        if (null === $issue) {
            throw new TransformationFailedException(sprintf(
                'An job with number "%s" does not exist!',
                $number
            ));
        }

        return $issue;
    }
}