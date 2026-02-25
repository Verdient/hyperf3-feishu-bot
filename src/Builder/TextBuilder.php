<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\FeiShu\Bot\Builder;

use Override;

/**
 * 文本构建器
 *
 * @author Verdient。
 */
class TextBuilder extends AbstractBuilder
{
    /**
     * @var string[] 文本内容
     *
     * @author Verdient。
     */
    protected array $texts;

    /**
     * 设置文本内容
     *
     * @param string $text
     *
     * @author Verdient。
     */
    public function set(string $text): static
    {
        $this->texts = [$text];

        return $this;
    }

    /**
     * 追加文本内容
     *
     * @param string $text
     *
     * @author Verdient。
     */
    public function append(string $text): static
    {
        $this->texts[] = "$text";

        return $this;
    }

    #[Override]
    public function toArray(): array
    {
        $text = implode("\n", $this->texts);

        if (!empty($this->mentionOpenIds)) {
            $text .= implode('', array_map(function ($openId) {
                return  "<at user_id=\"$openId\">User</at>";
            }, $this->mentionOpenIds));
        }

        return [
            'msg_type' => 'text',
            'content' => [
                'text' => $text,
            ],
        ];
    }
}
