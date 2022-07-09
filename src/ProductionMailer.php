<?php

declare(strict_types=1);

namespace Mailer;

class ProductionMailer implements MailerInterface
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
    public function send(string $to, string $subject, string $message, array|string $additionalHeaders = [], ?string $additionalParams = null): bool
    {
        // 本番環境は送信制限しない

        return mb_send_mail($to, $subject, $message, $additionalHeaders, $additionalParams);
    }
}
