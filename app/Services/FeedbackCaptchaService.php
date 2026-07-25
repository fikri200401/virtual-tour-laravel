<?php

namespace App\Services;

use Illuminate\Contracts\Session\Session;

class FeedbackCaptchaService
{
    private const SESSION_KEY = 'feedback_captcha_code';

    private const CHARACTERS = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

    public function issue(Session $session): string
    {
        $code = $this->generateCode();
        $session->put(self::SESSION_KEY, $code);

        return $this->renderImage($code);
    }

    public function verify(Session $session, string $answer): bool
    {
        $expected = $session->pull(self::SESSION_KEY);
        if (! is_string($expected) || $expected === '') {
            return false;
        }

        return hash_equals($expected, strtoupper(trim($answer)));
    }

    private function generateCode(): string
    {
        $code = '';
        $lastIndex = strlen(self::CHARACTERS) - 1;

        for ($index = 0; $index < 5; $index++) {
            $code .= self::CHARACTERS[random_int(0, $lastIndex)];
        }

        return $code;
    }

    private function renderImage(string $code): string
    {
        $canvas = imagecreatetruecolor(220, 70);
        $background = imagecolorallocate($canvas, random_int(225, 245), random_int(230, 248), 255);
        imagefill($canvas, 0, 0, $background);

        for ($index = 0; $index < 8; $index++) {
            $lineColor = imagecolorallocate($canvas, random_int(80, 170), random_int(90, 180), random_int(130, 210));
            imageline(
                $canvas,
                random_int(0, 220),
                random_int(0, 70),
                random_int(0, 220),
                random_int(0, 70),
                $lineColor
            );
        }

        foreach (str_split($code) as $index => $character) {
            $characterCanvas = imagecreatetruecolor(18, 24);
            imagesavealpha($characterCanvas, true);
            $transparent = imagecolorallocatealpha($characterCanvas, 0, 0, 0, 127);
            imagefill($characterCanvas, 0, 0, $transparent);
            $textColor = imagecolorallocate($characterCanvas, random_int(15, 60), random_int(35, 80), random_int(80, 145));
            imagestring($characterCanvas, 5, 4, 4, $character, $textColor);

            $rotated = imagerotate($characterCanvas, random_int(-18, 18), $transparent);
            imagecopyresampled(
                $canvas,
                $rotated,
                22 + ($index * 38),
                random_int(14, 23),
                0,
                0,
                27,
                36,
                imagesx($rotated),
                imagesy($rotated)
            );

            imagedestroy($rotated);
            imagedestroy($characterCanvas);
        }

        for ($index = 0; $index < 70; $index++) {
            $dotColor = imagecolorallocate($canvas, random_int(80, 190), random_int(90, 190), random_int(120, 220));
            imagesetpixel($canvas, random_int(0, 219), random_int(0, 69), $dotColor);
        }

        ob_start();
        imagepng($canvas);
        $png = ob_get_clean();
        imagedestroy($canvas);

        return is_string($png) ? $png : '';
    }
}
