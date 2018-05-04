<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 03/05/18
 * Time: 23:10
 */

namespace App\Tools;

use App\Entity\User;
use Mailgun\Mailgun;
use Mailgun\Model\Message\SendResponse;

class EMailSender
{
    /**
     * @var Mailgun
     */
    private $mailgun;

    /**
     * EMailSender constructor.
     * @param Mailgun $mailgun
     */
    public function __construct(Mailgun $mailgun)
    {
        $this->mailgun = $mailgun;
    }

    /**
     * Send a email with a specific template
     * @param User $to
     * @param string $template
     * @param array $params
     * @return SendResponse
     */
    public function send(User $to, string $template, array $params) {
        return $this->mailgun->messages()->send('mb.bdesprez.com', [
            'from' => 'BdzConso <mailgun@bdesprez.com>',
            'to' => $to->getFirstname() . ' ' . $to->getLastname() . ' <' . $to->getEmail() . '>',
            'subject' => 'Password reset',
            'html' => $this->renderView("email/{$template}.html.twig", $params),
            'text' => $this->renderView("email/{$template}.txt.twig", $params)
        ]);
    }
}