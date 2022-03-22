<?php
declare(strict_types=1);

namespace Api\Dto;

use JMS\Serializer\Annotation;

/**
 * @Annotation\ExclusionPolicy("none")
 */
final class BookOutput implements Dto
{
    /**
     * @var string
     * @Annotation\Expose()
     * @Annotation\Type("string")
     */
    public $name;

    /**
     * @var string
     * @Annotation\Expose()
     * @Annotation\Type("string")
     */
    public $author;

    /**
     * @var string
     * @Annotation\Expose()
     * @Annotation\Type("string")
     */
    public $cover;

    /**
     * @var string
     * @Annotation\Expose()
     * @Annotation\Type("string")
     */
    public $file;

    /**
     * @var datetime
     * @Annotation\Expose()
     * @Annotation\Type("DateTime<'d-m-Y\Th:i:s'>")
     */
    public $last_read_datetime;

    /**
     * @var boolean
     * @Annotation\Expose()
     * @Annotation\Type("boolean")
     */
    public $is_downloadable;
}
