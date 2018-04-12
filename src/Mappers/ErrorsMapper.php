<?php

namespace FreddieGar\JsonApiMapper\Mappers;

use FreddieGar\JsonApiMapper\Contracts\DocumentInterface;
use FreddieGar\JsonApiMapper\Contracts\ErrorsMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\LinksMapperInterface;
use FreddieGar\JsonApiMapper\Helper;
use FreddieGar\JsonApiMapper\Traits\LinksMapperTrait;
use FreddieGar\JsonApiMapper\Traits\MetaMapperTrait;

/**
 * Class ErrorsMapper
 * @package FreddieGar\JsonApiMapper\Mappers
 *
 * @method string id() Alias to getId() method
 * @method string about() Alias to getAbout() method
 * @method int status() Alias to getStatus() method
 * @method string code() Alias to getCode() method
 * @method string title() Alias to getTitle() method
 * @method string detail() Alias to getDetail() method
 * @method array source() Alias to getSource() method
 * @method string meta(string $path = null)  Alias to getMeta() method
 * @method LinksMapperInterface links() Alias to getLinks() method
 */
class ErrorsMapper extends Loader implements ErrorsMapperInterface
{
    use LinksMapperTrait,
        MetaMapperTrait;

    public function load($input, ?string $tag = DocumentInterface::KEYWORD_ERRORS): ErrorsMapperInterface
    {
        return parent::load($input, $tag);
    }

    public function get(?int $index = null): ?ErrorsMapperInterface
    {
        if (!is_null($index)) {
            if (isset($this->original()[$index])) {
                $this->current = $this->original()[$index];
            } else {
                return null;
            }
        }

        return $this;
    }

    public function getId(): ?string
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_ERRORS_ID, null);
    }

    /**
     * @return null|string
     */
    public function getAbout(): ?string
    {
        return $this->getLinks()->getAbout();
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_ERRORS_STATUS, null);
    }

    /**
     * @return null|string
     */
    public function getCode(): ?string
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_ERRORS_CODE, null);
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_ERRORS_TITLE, null);
    }

    /**
     * @return null|string
     */
    public function getDetail(): ?string
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_ERRORS_DETAIL, null);
    }

    /**
     * @return array|null
     */
    public function getSource(): ?array
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_ERRORS_SOURCE, null);
    }
}
