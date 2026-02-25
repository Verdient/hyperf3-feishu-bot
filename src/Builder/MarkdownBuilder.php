<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\FeiShu\Bot\Builder;

use Override;

/**
 * Markdown 构建器
 *
 * @author Verdient。
 */
class MarkdownBuilder extends AbstractBuilder
{
    /**
     * 标题
     *
     * @author Verdient。
     */
    protected ?string $title = null;

    /**
     * 段落
     *
     * @author Verdient。
     */
    protected array $paragraphs = [];

    /**
     * 语言
     *
     * @author Verdient。
     */
    protected string $language = 'zh_cn';

    /**
     * 设置消息标题
     *
     * @param ?string $title 消息标题
     *
     * @author Verdient。
     */
    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * 设置语言
     *
     * @param string $language 语言
     *
     * @author Verdient。
     */
    public function setLanguage(string $language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * 构建文本标签
     *
     * @param string $text 文本
     *
     * @author Verdient。
     */
    protected function buildText(string $text): array
    {
        return [
            'tag' => 'text',
            'text' => $text
        ];
    }

    /**
     * 构建 at 标签
     *
     * @param string $openId 开放编号
     * @param string $username 用户名
     *
     * @author Verdient。
     */
    protected function buildAt(string $openId, ?string $username = null): array
    {
        return [
            'tag' => 'at',
            'user_id' => $openId,
            'user_name' => $username,
        ];
    }

    /**
     * 追加段落
     *
     * @param array ...$paragraphs 段落
     *
     * @author Verdient。
     */
    protected function appendParagraphs(array ...$paragraphs)
    {
        $this->paragraphs[] = $paragraphs;
    }

    /**
     * 追加文本
     *
     * @param string $text 文本内容
     *
     * @author Verdient。
     */
    public function appendText(string $text): static
    {
        $this->appendParagraphs($this->buildText($text));

        return $this;
    }

    /**
     * 追加超链接
     *
     * @param string $text 超链接文本内容
     * @param string $href 超链接地址
     * @param string $label 左侧前缀文本内容
     *
     * @author Verdient。
     */
    public function appendLink(string $url, string $text, ?string $label = null)
    {
        $paragraphs = [];

        if ($label) {
            $paragraphs[] = $this->buildText($label);
        }

        $paragraphs[] = [
            'tag' => 'a',
            'text' => $text,
            'href' => $url
        ];

        $this->appendParagraphs(...$paragraphs);

        return $this;
    }

    /**
     * 追加图片
     *
     * @param string $imageKey 图片的唯一标识
     *
     * @return static
     */
    public function appendImage(string $imageKey)
    {
        $this->appendParagraphs([
            'tag' => 'img',
            'image_key' => $imageKey
        ]);

        return $this;
    }

    /**
     * 追加提及（@）开放身份编号
     *
     * @param array $openIds 用户的 飞书 open_id
     * @param string $username 用户名
     *
     * @author Verdient。
     */
    public function appendAtOpenIds(array $openIds, ?string $username = null): static
    {
        if (empty($openIds)) {
            return $this;
        }

        $paragraphs = [];

        foreach ($openIds as $openId) {
            $paragraphs[] = $this->buildAt($openId, $username);
        }

        $this->appendParagraphs(...$paragraphs);

        return $this;
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function toArray(): array
    {
        $paragraphs = $this->paragraphs;

        if (!empty($this->mentionOpenIds)) {
            $ats = [];

            foreach ($this->mentionOpenIds as $openId) {
                $ats[] = $this->buildAt($openId);
            }

            $paragraphs[] = $ats;
        }

        return [
            'msg_type' => 'post',
            'content' => [
                'post' => [
                    $this->language => [
                        'title' => $this->title,
                        'content' => $paragraphs,
                    ]
                ],
            ],
        ];
    }
}
