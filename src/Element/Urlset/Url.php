<?php declare(strict_types = 1);

namespace Sitemaped\Element\Urlset;

use Sitemaped\Element\Element;
use Sitemaped\Element\NS;

class Url extends Element
{
    public const NAME = 'url';
    public const CHANGEFREQ_ALWAYS = 'always';
    public const CHANGEFREQ_HOURLY = 'hourly';
    public const CHANGEFREQ_DAILY = 'daily';
    public const CHANGEFREQ_WEEKLY = 'weekly';
    public const CHANGEFREQ_MONTHLY = 'monthly';
    public const CHANGEFREQ_YEARLY = 'yearly';
    public const CHANGEFREQ_NEVER = 'never';

    /**
     * @var string
     */
    protected $loc;
    /**
     * @var \DateTime|string|null
     */
    protected $lastmod;
    /**
     * @var string|null
     */
    protected $changefreq;
    /**
     * @var float|null
     */
    protected $priority;

    /**
     * @var array
     */
    protected $images = [];
    /**
     * @var array
     */
    protected $videos = [];
    /**
     * @var array
     */
    protected $news = [];

    /**
     * @param string $loc
     * @param string|\DateTime $lastmod
     * @param string|null $changefreq
     * @param float|null $priority
     */
    public function __construct(string $loc, $lastmod = null, string $changefreq = null, float $priority = null)
    {
        parent::__construct(self::NAME);
        $this->loc = $loc;
        $this->lastmod = $lastmod;
        $this->changefreq = $changefreq;
        $this->priority = (string) $priority;
    }

    /**
     * @return string
     */
    public function getLoc(): string
    {
        return $this->loc;
    }

    /**
     * @param Image $image
     */
    public function addImage(Image $image)
    {
        if (count($this->images) >= 1000) {
            throw new \DomainException('Images limit reached');
        }
        $this->images[] = $image;
    }

    /**
     * @param Video $video
     */
    public function addVideo(Video $video)
    {
        $this->videos[] = $video;
    }

    /**
     * @param News $news
     */
    public function addNews(News $news)
    {
        $this->news[] = $news;
    }

    /**
     * @return \DateTime|string|null
     */
    public function getLastmod()
    {
        return $this->lastmod;
    }

    /**
     * @param \DateTime|string|null $lastmod
     */
    public function setLastmod($lastmod): void
    {
        $this->lastmod = $lastmod;
    }

    /**
     * @return string|null
     */
    public function getChangefreq(): ?string
    {
        return $this->changefreq;
    }

    /**
     * @param string|null $changefreq
     */
    public function setChangefreq(?string $changefreq): void
    {
        $this->changefreq = $changefreq;
    }

    /**
     * @return float|null
     */
    public function getPriority(): ?float
    {
        return $this->priority;
    }

    /**
     * @param float|null $priority
     */
    public function setPriority(?float $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @param array $images
     */
    public function setImages(array $images): void
    {
        $this->images = $images;
    }

    /**
     * @return array
     */
    public function getVideos(): array
    {
        return $this->videos;
    }

    /**
     * @param array $videos
     */
    public function setVideos(array $videos): void
    {
        $this->videos = $videos;
    }

    /**
     * @return array
     */
    public function getNews(): array
    {
        return $this->news;
    }

    /**
     * @param array $news
     */
    public function setNews(array $news): void
    {
        $this->news = $news;
    }

    /**
     * @return Element[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param string $href
     * @param string $lang
     */
    public function addAlternate(string $href, string $lang): void
    {
        $this->addChild(new Element('link', null, 'xhtml', new NS('http://www.w3.org/1999/xhtml', 'xmlns:xhtml'), [
            'rel' => 'alternate',
            'hreflang' => $lang,
            'href' => $href,
        ]));
    }

    /**
     * @return mixed[]
     */
    public function getValue(): array
    {
        $lastmod = $this->lastmod;
        if ($lastmod !== null && !($lastmod instanceof \DateTime)) {
            $lastmod = new \DateTime($this->lastmod);
        }

        $value = [
            new Element('loc', $this->loc),
            $this->lastmod ? new Element('lastmod', $lastmod->format(\DateTime::W3C)) : null,
            $this->changefreq ? new Element('changefreq', $this->changefreq) : null,
            $this->priority ? new Element('priority', $this->priority) : null,
            $this->children,
        ];
        return \array_merge(\array_filter($value), $this->images, $this->videos, $this->news);
    }
}
