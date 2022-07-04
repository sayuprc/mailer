<?php

declare(strict_types=1);

namespace Mailer;

use Mailer\Exception\NotAllowedEmailAddressException;

class DevelopmentMailer implements MailerInterface
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
     * @throws NotAllowedEmailAddressException
     *
     * @return bool
     */
    public function send(string $to, string $subject, string $message, array|string $additionalHeaders = [], ?string $additionalParams = null): bool
    {
        if (! $this->canSend($to)) {
            throw new NotAllowedEmailAddressException($to . ' is not allowed email address.');
        }

        foreach ($this->getCcs($additionalHeaders) as $cc) {
            if (! $this->canSend($cc)) {
                throw new NotAllowedEmailAddressException($cc . ' is not allowed email address.');
            }
        }

        return mb_send_mail($to, $subject, $message, $additionalHeaders, $additionalParams);
    }

    /**
     * 送信してもよいアドレスかチェックする
     *
     * @param string $email
     *
     * @return bool
     */
    private function canSend(string $email): bool
    {
        // テスト環境で送信してもいいアドレスかチェックする
        return preg_match('//', $email) === 1 ? true : false;
    }

    /**
     * CcとBccを取得する。
     *
     * @param array|string $additionalHeaders
     *
     * @return array
     */
    private function getCcs(array|string $additionalHeaders = []): array
    {
        $headers = is_string($additionalHeaders) ? explode("\r\n", $additionalHeaders) : $additionalHeaders;

        $mapped = array_map(
            fn ($header) => explode(',', preg_replace('/(Bcc|Cc):\s?/i', '', $header)),
            array_filter($headers, [$this, 'isCcOrBcc'])
        );

        $ccs = [];

        // メールアドレスをフラットにする
        foreach ($mapped as $cc) {
            $ccs = [...$ccs, ...$cc];
        }

        return $ccs;
    }

    /**
     * CcもしくはBccかのチェック
     *
     * @param string $value
     *
     * @return bool
     */
    private function isCcOrBcc(string $value): bool
    {
        $value = strtolower($value);

        return str_starts_with($value, 'cc:') || str_starts_with($value, 'bcc:');
    }
}
