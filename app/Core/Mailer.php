<?php
declare(strict_types=1);

namespace App\Core;

use RuntimeException;

/**
 * Envoi d'emails texte en UTF-8.
 * - Si le SMTP est activé dans la config : connexion SMTP directe (STARTTLS/SSL + AUTH LOGIN),
 *   compatible Gmail avec un mot de passe d'application.
 * - Sinon : fonction mail() de PHP (fonctionne sur la plupart des hébergeurs, pas sous WAMP).
 */
final class Mailer
{
    public static function send(string $to, string $subject, string $body, ?string $replyTo = null, ?string $replyName = null): bool
    {
        $c = config('mail');
        $fromEmail = $c['smtp']['enabled'] && $c['smtp']['user'] ? $c['smtp']['user'] : $c['from'];

        $headers = [
            'Date: ' . date('r'),
            'From: ' . self::encode($c['from_name']) . " <$fromEmail>",
            'To: <' . $to . '>',
            'Subject: ' . self::encode($subject),
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8',
            'Content-Transfer-Encoding: base64',
            'Message-ID: <' . bin2hex(random_bytes(12)) . '@' . (explode('@', $fromEmail)[1] ?? 'localhost') . '>',
        ];
        if ($replyTo && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
            $headers[] = 'Reply-To: ' . ($replyName ? self::encode($replyName) . ' ' : '') . "<$replyTo>";
        }
        $encodedBody = rtrim(chunk_split(base64_encode($body), 76, "\r\n"));

        try {
            if ($c['smtp']['enabled']) {
                self::smtp($c['smtp'], $fromEmail, $to, $headers, $encodedBody);
                return true;
            }
            // mail() ajoute lui-même To et Subject
            $extra = array_filter($headers, fn ($h) => !str_starts_with($h, 'To:') && !str_starts_with($h, 'Subject:'));
            return @mail($to, self::encode($subject), $encodedBody, implode("\r\n", $extra));
        } catch (\Throwable $e) {
            log_error('Mailer: ' . $e->getMessage());
            return false;
        }
    }

    private static function encode(string $text): string
    {
        return '=?UTF-8?B?' . base64_encode($text) . '?=';
    }

    private static function smtp(array $s, string $from, string $to, array $headers, string $body): void
    {
        $remote = ($s['secure'] === 'ssl' ? 'ssl://' : 'tcp://') . $s['host'] . ':' . $s['port'];
        $fp = @stream_socket_client($remote, $errno, $errstr, 20);
        if (!$fp) {
            throw new RuntimeException("Connexion SMTP impossible : $errstr ($errno)");
        }
        stream_set_timeout($fp, 20);

        $read = function () use ($fp): string {
            $data = '';
            while (($line = fgets($fp, 515)) !== false) {
                $data .= $line;
                if (isset($line[3]) && $line[3] === ' ') {
                    break;
                }
            }
            return $data;
        };
        $expect = function (string $response, array $codes): void {
            if (!in_array((int) substr($response, 0, 3), $codes, true)) {
                throw new RuntimeException('SMTP : ' . trim($response));
            }
        };
        $cmd = function (string $command, array $codes) use ($fp, $read, $expect): void {
            fwrite($fp, $command . "\r\n");
            $expect($read(), $codes);
        };

        try {
            $expect($read(), [220]);
            $cmd('EHLO ylcd.local', [250]);
            if ($s['secure'] === 'tls') {
                $cmd('STARTTLS', [220]);
                if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    throw new RuntimeException('Échec de la négociation TLS.');
                }
                $cmd('EHLO ylcd.local', [250]);
            }
            if ($s['user'] !== '') {
                $cmd('AUTH LOGIN', [334]);
                $cmd(base64_encode($s['user']), [334]);
                $cmd(base64_encode($s['pass']), [235]);
            }
            $cmd("MAIL FROM:<$from>", [250]);
            $cmd("RCPT TO:<$to>", [250, 251]);
            $cmd('DATA', [354]);
            fwrite($fp, implode("\r\n", $headers) . "\r\n\r\n" . $body . "\r\n.\r\n");
            $expect($read(), [250]);
            fwrite($fp, "QUIT\r\n");
        } finally {
            fclose($fp);
        }
    }
}
