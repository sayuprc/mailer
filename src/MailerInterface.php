<?php

declare(strict_types=1);

namespace Mailer;

interface MailerInterface
{
    /**
     * メール送信
     *
     * @param string       $to
     * @param string       $subject
     * @param string       $message
     * @param array|string $additionalHeaders
     * @param ?string      $additionalParams
     *
     * @return bool
     */
    public function send(string $to, string $subject, string $message, array|string $additionalHeaders = [], ?string $additionalParams = null): bool;
}
