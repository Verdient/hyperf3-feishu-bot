<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\FeiShu\Bot;

use Verdient\Hyperf3\FeiShu\Bot\Builder\BuilderInterface;
use Verdient\HttpAPI\ResultInterface;
use Verdient\Hyperf3\FeiShu\FeiShu;

/**
 * 飞书机器人
 *
 * @link https://open.feishu.cn/document/client-docs/bot-v3/add-custom-bot
 *
 * @author Verdient。
 */
class FeiShuBot
{
    /**
     * @param string $key 机器人标识
     * @param string $secret 签名密钥，为空不校验
     *
     * @author Verdient。
     */
    public function __construct(protected string $key, protected ?string $secret = null) {}

    /**
     * 创建新的实例
     *
     * @param string $key 机器人标识
     * @param string $secret 签名密钥，为空不校验
     *
     * @author Verdient。
     */
    public static function create(string $key, ?string $secret = null)
    {
        return new static($key, $secret);
    }

    /**
     * 生成签名字符串
     *
     * @param mixed $secret 机器人签名密钥
     * @param mixed $timestamp 时间戳
     *
     * @author Verdient。
     */
    protected function generateSign($secret, $timestamp)
    {
        $string = $timestamp . "\n" . $secret;
        $signature = hash_hmac('sha256', '', $string, true);

        $encodedSignature = base64_encode($signature);

        return $encodedSignature;
    }

    /**
     * 发送消息
     *
     * @param BuilderInterface $builder 构建器
     *
     * @author Verdient。
     */
    public function send(BuilderInterface $builder): ResultInterface
    {
        $bodies = $builder->toArray();

        if ($this->secret) {
            $timestamp = time();

            $bodies['timestamp'] = $timestamp;
            $bodies['sign'] = $this->generateSign($this->secret, $timestamp);
        }

        return FeiShu::create()
            ->request('open-apis/bot/v2/hook/' . $this->key)
            ->setMethod('POST')
            ->setBodies($bodies)
            ->send();
    }
}
