<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\FeiShu\Bot\Builder;

use Override;

/**
 * 模板构建器
 *
 * @author Verdient。
 */
class TemplateBuilder extends AbstractBuilder
{
    /**
     * 模版编号
     *
     * @author Verdient。
     */
    protected ?string $templateId = null;

    /**
     * 模版版本
     *
     * @author Verdient。
     */
    protected ?string $version = null;

    /**
     * 变量集合
     *
     * @author Verdient。
     */
    protected array $variables = [];

    /**
     * 设置模板编号
     *
     * @param string $templateId 模板编号
     *
     * @author Verdient。
     */
    public function setTemplateId(string $templateId, ?string $version = null): static
    {
        $this->templateId = $templateId;

        if ($version !== null) {
            $this->version = $version;
        }

        return $this;
    }

    /**
     * 设置版本
     *
     * @param string $version 版本
     *
     * @author Verdient。
     */
    public function setVersion(string $version): static
    {
        $this->version = $version;

        return $this;
    }

    /**
     * 设置模板变量
     *
     * @param string $key 键名
     * @param string $value 键值
     *
     * @author Vrdient。
     */
    public function setVariable(string $key, string $value): static
    {
        $this->variables[$key] = $value;

        return $this;
    }

    /**
     * 批量设置模板变量
     *
     * @param array $variables 变量集合
     *
     * @author Verdient。
     */
    public function setVariables(array $variables): static
    {
        $this->variables = $variables;

        return $this;
    }

    /**
     * 批量设置模板变量
     *
     * @param array $variables 变量集合
     *
     * @author Verdient。
     */
    public function mergeVariables(array $variables): static
    {
        $this->variables = array_merge($this->variables, $variables);

        return $this;
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function toArray(): array
    {
        return [
            'msg_type' => 'interactive',
            'card' => [
                'type' => 'template',
                'data' => [
                    'template_id' => $this->templateId,
                    'template_version_name' => $this->version,
                    'template_variable' => $this->variables,
                ],
            ],
        ];
    }
}
