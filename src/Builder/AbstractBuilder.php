<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\FeiShu\Bot\Builder;

use Override;

/**
 * 抽象构建器
 *
 * @author Verdient。
 */
abstract class AbstractBuilder implements BuilderInterface
{
    /**
     * 提及的开放身份编号
     *
     * @author Verdient。
     */
    protected array $mentionOpenIds = [];

    /**
     * 创建新的实例
     *
     * @author Verdient。
     */
    public static function create(): static
    {
        return new static();
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function atOpenIds(array $openIds): static
    {
        $this->mentionOpenIds = $openIds;

        return $this;
    }
}
