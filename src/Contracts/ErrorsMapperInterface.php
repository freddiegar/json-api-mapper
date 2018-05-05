<?php

namespace FreddieGar\JsonApiMapper\Contracts;

/**
 * Interface ErrorsMapperInterface
 * @package FreddieGar\JsonApiMapper\Contracts
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
 *
 * @property string id Access magic to getId() method
 * @property string about Access magic to getAbout() method
 * @property int status Access magic to getStatus() method
 * @property string code Access magic to getCode() method
 * @property string title Access magic to getTitle() method
 * @property string detail Access magic to getDetail() method
 * @property array source Access magic to getSource() method
 * @property LinksMapperInterface links Access magic to getLinks() method
 */
interface ErrorsMapperInterface extends LoaderInterface
{
    /**
     * @param int|null $index
     * @return ErrorsMapperInterface
     */
    public function get(?int $index = null): ?ErrorsMapperInterface;

    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * @return null|string
     */
    public function getAbout(): ?string;

    /**
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * @return null|string
     */
    public function getCode(): ?string;

    /**
     * @return null|string
     */
    public function getTitle(): ?string;

    /**
     * @return null|string
     */
    public function getDetail(): ?string;

    /**
     * @return array|null
     */
    public function getSource(): ?array;

    /**
     * @param null|string $path
     * @return mixed|null
     */
    public function getMeta(?string $path = null);

    /**
     * @return null|LinksMapperInterface
     */
    public function getLinks(): ?LinksMapperInterface;
}
