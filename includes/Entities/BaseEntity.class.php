<?php declare ( strict_types = 1 );

namespace DDMS\Entities;

class BaseEntity
{
    /**
     *
     * @param int $id generic id of the object, can be int or string type (for sessions)
     * @param string $name generic name of the object
     */
    public function __construct( $id, string $name = '' )
    {
        $this->id = (int) $id;
        $this->ID = (int) $id;
        $this->name = $name;
        $this->title = $name;
    }

    /**
     *
     * @return array
     */
    public function getAsArray(): array
    {
        if ( empty( $this->id ) ) {
            return [];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
