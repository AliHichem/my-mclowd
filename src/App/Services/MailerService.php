<?php
namespace App\Services;

use \Twig_Environment;
use \Swift_Mailer;

class MailerService
{
    /** @var string */
    private $fromAddress;

    /** @var \Twig_Environment */
    private $twig;

    /** @var \Swift_Mailer */
    private $mailer;

    /**
     * @param Swift_Mailer $mailer
     * @param Twig_Environment $twig
     * @param $fromAddress
     */
    public function __construct(Swift_Mailer $mailer, Twig_Environment $twig, $fromAddress)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->fromAddress = $fromAddress;
    }

    /**
     * @param string $recipients email address of recipient(s)
     * @param string $templateName name of template for email contents
     * @param array $templateParameters parameters passed to template
     * @param null $from from email address, if null uses service default provided
     */
    public function sendEmail($recipients, $templateName, array $templateParameters, $from = null)
    {
        if (null === $from) {
            $from = $this->fromAddress;
        }

        $template = $this->twig->loadTemplate($templateName);

        $subject = trim($template->renderBlock('subject', $templateParameters));
        $text = trim($template->renderBlock('text', $templateParameters));
        $html = trim($template->renderBlock('html', $templateParameters));

        $message = \Swift_Message::newInstance()
                ->setFrom($from)
                ->setSubject($subject);

        if (is_array($recipients)) {
            foreach ($recipients as $recipient) {
                $message->addTo($recipient);
            }
        } else {
            $message->setTo($recipients);
        }

        if (!empty($html)) {
            $message->setBody($html, 'text/html')->addPart($text, 'text/plain');
        } else {
            $message->setBody($text);
        }

        $this->mailer->send($message);
    }

    /**
     * Sends example email just to proof concept
     *
     * @param string $email recipient
     */
    public function sendExampleEmail($email)
    {
        $this->sendEmail($email, 'App:Mailer:emails/example_email.html.twig', array());
    }
}
