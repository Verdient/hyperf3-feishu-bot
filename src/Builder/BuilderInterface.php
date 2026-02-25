<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\FeiShu\Bot\Builder;

use Hyperf\Contract\Arrayable;

/**
 * 构建器接口
 *
 * @author Verdient。
 */
interface BuilderInterface extends Arrayable
{
    /**
     * 提及（@）开放身份编号
     *
     * @author Verdient。
     */
    public function atOpenIds(array $openIds): static;
}
