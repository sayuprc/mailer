<?php

declare(strict_types=1);

namespace tests;

use Mailer\DevelopmentMailer;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class DevelopmentMailerTest extends TestCase
{
    /**
     * protected/privateメソッドを取得
     *
     * @param string $methodName
     *
     * @return array<DevelopmentMailer|ReflectionMethod>
     */
    private function getNonPublicMethod(string $methodName): array
    {
        $mailer = new DevelopmentMailer();

        $reflection = new ReflectionClass($mailer);

        $method = $reflection->getMethod($methodName);

        $method->setAccessible(true);

        return [$mailer, $method];
    }

    /**
     * メール送信テスト
     *
     * @return void
     */
    public function testSendMail(): void
    {
        $mailer = new DevelopmentMailer();

        $to = 'sayuprc@example.com';
        $subject = '件名';
        $message = '本文';
        $headers = "From: sayuprc@example.com\r\nCc: sayuprc@example.com";
        // 仮想環境だとエンベロープがあるとエラーになるので、設定しない

        $result = $mailer->send($to, $subject, $message, $headers);

        $this->assertTrue($result);
    }

    /**
     * CcとBccの配列を取得するテスト
     *
     * @return void
     */
    public function testGetCcs(): void
    {
        [$mailer, $method] = $this->getNonPublicMethod('getCcs');

        // Cc
        $params = ['Cc: sayuprc@example.com'];

        $actual = $method->invokeArgs($mailer, $params);

        $this->assertCount(1, $actual);

        // Cc(複数)
        $params = ['Cc: sayuprc@example.com,sayuprc@example.co.jp'];

        $actual = $method->invokeArgs($mailer, $params);

        $this->assertCount(2, $actual);

        // Bcc
        $params = ['Bcc: sayuprc@example.com'];

        $actual = $method->invokeArgs($mailer, $params);

        $this->assertCount(1, $actual);

        // Bcc(複数)
        $params = ['Bcc: sayuprc@example.com,sayuprc@example.co.jp'];

        $actual = $method->invokeArgs($mailer, $params);

        $this->assertCount(2, $actual);
    }

    /**
     * 文字列がCcかBccであるかをチェックする関数のテスト
     *
     * @return void
     */
    public function testIsCcOrBcc(): void
    {
        [$mailer, $method] = $this->getNonPublicMethod('isCcOrBcc');

        // Cc
        $params = ['Cc: sayuprc@example.com'];

        $actual = $method->invokeArgs($mailer, $params);

        $this->assertTrue($actual);

        // Cc(複数)
        $params = ['Cc: sayuprc@example.com,sayuprc@example.co.jp'];

        $actual = $method->invokeArgs($mailer, $params);

        $this->assertTrue($actual);

        // Bcc
        $params = ['Bcc: sayuprc@example.com'];

        $actual = $method->invokeArgs($mailer, $params);

        $this->assertTrue($actual);

        // Bcc(複数)
        $params = ['Bcc: sayuprc@example.com,sayuprc@example.co.jp'];

        $actual = $method->invokeArgs($mailer, $params);

        $this->assertTrue($actual);

        // CcもBccもない
        $params = ['From: sayuprc@example.com'];

        $actual = $method->invokeArgs($mailer, $params);

        $this->assertFalse($actual);
    }
}
