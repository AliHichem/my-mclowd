<?php
namespace App\Controller;

use App\Entity\
use Doctrine\Common\Persistence\PersistentObject;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;

class JobsController extends Controller
{
    
    /**
     * @Secure(roles="IS_AUTHENTICATED_FULLY")           
     */
    public function inboxAction()
    {                        
        $provider = $container->get('fos_message.provider');
        $threads = $provider->getInboxThreads();

        return array(
            'threads' => $threads,
            );
    }

    /**
     * @Secure(roles="IS_AUTHENTICATED_FULLY")           
     */
    public function outboxAction()
    {
        $threads = $provider->getSentThreads();
        return array(
            'threads' => $threads,
            );
    }
}