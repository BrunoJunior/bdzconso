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
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class EMailSender
{
    /**
     * @var Mailgun
     */
    private Mailgun $mailgun;

    /**
     * @var Environment
     */
    private Environment $twig;

    /**
     * EMailSender constructor.
     * @param Mailgun $mailgun
     * @param Environment $twig
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
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function send(User $to, string $template, array $params): SendResponse {
        return $this->mailgun->messages()->send('mb.bdesprez.com', [
            'from' => 'BdzConso <mailgun@bdesprez.com>',
            'to' => $to->getFirstname() . ' ' . $to->getLastname() . ' <' . $to->getEmail() . '>',
            'subject' => 'Password reset',
            'html' => $this->twig->render("email/{$template}.html.twig", $params),
            'text' => $this->twig->render("email/{$template}.txt.twig", $params)
        ]);
    }
}
