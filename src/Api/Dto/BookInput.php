<?php
declare(strict_types=1);

namespace Api\Dto;

use JMS\Serializer\Annotation;

/**
 * @Annotation\ExclusionPolicy("none")
 */
final class BookInput implements Dto
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
     * @var datetime
     * @Annotation\Expose()
     * @Annotation\Type("DateTime<'Y-m-d\Th:i:s'>")
     */
    public $last_read_datetime;

    /**
     * @var boolean
     * @Annotation\Expose()
     * @Annotation\Type("boolean")
     */
    public $is_downloadable;
}
