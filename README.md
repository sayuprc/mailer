# Mailer

`mb_send_mail` 関数のラッパークラス

## 作った経緯

開発環境でメール送信機能の誤送信を防ぐために作成した。

## 使い方

開発時に`DevelopmentMailer`クラスを利用することで、特定のメールアドレス以外に送信できないようにします。

DIコンテナと組み合わせて利用すると、より使い勝手がよくなります。

```php
$to = 'hoge@example.com';
$subject = 'subject of the email';
$message = 'email content';
$headers = "From: fuga@example.com\rCc: piyo@example.com\nBcc: hogehoge@example.com";
$params = '-fhoge@example.com';

$mailer = new DevelopmentMailer();

$sendResult = $mailer->send($to, $subject, $message, $headers, $params);
```