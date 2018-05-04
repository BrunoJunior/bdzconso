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
use Twig\Environment;

class EMailSender
{
    /**
     * @var Mailgun
     */
    private $mailgun;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * EMailSender constructor.
     * @param Mailgun $mailgun
     */
    public function __construct(Mailgun $mailgun, Environment $twig)
    {
        $this->mailgun = $mailgun;
        $this->twig = $twig;
    }

    /**
     * Send a email with a specific template
     * @param User $to
     * @param string $template
     * @param array $params
     * @return SendResponse
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function send(User $to, string $template, array $params) {
        return $this->mailgun->messages()->send('mb.bdesprez.com', [
            'from' => 'BdzConso <mailgun@bdesprez.com>',
            'to' => $to->getFirstname() . ' ' . $to->getLastname() . ' <' . $to->getEmail() . '>',
            'subject' => 'Password reset',
            'html' => $this->twig->render("email/{$template}.html.twig", $params),
            'text' => $this->twig->render("email/{$template}.txt.twig", $params)
        ]);
    }
}