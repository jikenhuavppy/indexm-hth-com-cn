<?php

/**
 * SiteMeta - 站点元信息管理工具
 *
 * 本文件定义了一个站点元信息存储与描述生成类，
 * 适合用于静态站点、简单 CMS 或 SEO 辅助场景。
 */

class SiteMeta
{
    private array $data;

    public function __construct(array $data = [])
    {
        $this->data = array_merge(self::defaults(), $data);
    }

    private static function defaults(): array
    {
        return [
            'title'       => '华体会',
            'domain'      => 'indexm-hth.com.cn',
            'description' => '华体会官方平台，提供丰富体育赛事与娱乐内容。',
            'keywords'    => ['华体会', '体育', '赛事', '娱乐', '平台'],
            'language'    => 'zh-CN',
            'charset'     => 'UTF-8',
            'author'      => '系统管理员',
        ];
    }

    /**
     * 获取指定字段，若不存在则返回默认值
     */
    public function get(string $key, mixed $default = ''): mixed
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * 设置或覆盖元信息
     */
    public function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * 以数组形式返回所有元数据
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * 生成简短 SEO 描述（通常用于 <meta> 标签）
     */
    public function shortDescription(int $maxLength = 70): string
    {
        $parts = [];
        $title = $this->get('title');
        $desc  = $this->get('description');
        $domain = $this->get('domain');

        if ($title) {
            $parts[] = $title;
        }
        if ($desc) {
            $parts[] = $desc;
        }
        if ($domain) {
            $parts[] = '官网：' . $domain;
        }

        $text = implode(' - ', $parts);

        if (mb_strlen($text) > $maxLength) {
            $text = mb_substr($text, 0, $maxLength - 3) . '...';
        }

        return $text;
    }

    /**
     * 生成适合放入 HTML 的 meta 标签字符串
     */
    public function metaTags(): string
    {
        $tags = [];
        $tags[] = '<meta charset="' . $this->escape($this->get('charset')) . '">';
        $tags[] = '<meta name="description" content="' . $this->escape($this->shortDescription(150)) . '">';
        $tags[] = '<meta name="keywords" content="' . $this->escape(implode(', ', $this->get('keywords', []))) . '">';
        $tags[] = '<meta name="author" content="' . $this->escape($this->get('author')) . '">';
        $tags[] = '<meta name="language" content="' . $this->escape($this->get('language')) . '">';

        return implode("\n", $tags);
    }

    /**
     * 安全转义 HTML 特殊字符
     */
    private function escape(string $str): string
    {
        return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * 返回站点 URL（基于配置的域名）
     */
    public function siteUrl(): string
    {
        $domain = $this->get('domain');
        return 'https://' . $domain;
    }

    /**
     * 静态工厂：基于默认数据快速创建实例
     */
    public static function createWithDefaults(): self
    {
        return new self();
    }
}

// ---------- 使用示例 ----------

// 实例化并修改部分元信息
$meta = new SiteMeta();
$meta->set('title', '华体会 - 体育娱乐平台');
$meta->set('description', '华体会提供最新体育赛事、真人娱乐及电子竞技服务。');

// 输出简短描述
echo $meta->shortDescription() . "\n";
// 输出 meta 标签
echo $meta->metaTags() . "\n";
// 输出站点 URL
echo $meta->siteUrl() . "\n";

// 获取原始数据
print_r($meta->all());