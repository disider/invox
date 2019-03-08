<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Tests\App\Fake;

class FakeEmail
{
    public $sender;
    public $subject;
    public $body;
    public $recipients = [];

    public function hasRecipient($recipient)
    {
        foreach ($this->recipients as $r) {
            if ($r === $recipient) {
                return true;
            }
        }

        return false;
    }
}
