<?php

namespace Corcel\Services;

use Corcel\Model\Option;
use Illuminate\Support\Arr;

/**
 * Class RoleManager
 *
 * @package Corcel\Services
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class RoleManager
{
    /**
     * @var string
     */
    protected $optionKey = 'wp_user_roles';

    /**
     * @var array
     */
    protected $option = [];

    /**
     * @var array
     */
    protected $capabilities = [];

    /**
     * @param string $role
     * @return $this
     */
    public function from($role)
    {
        $this->option = Option::get($this->optionKey);
        $role = Arr::get($this->option, $role);
        $this->capabilities = Arr::get($role, 'capabilities');

        return $this;
    }

    /**
     * @param string $name
     * @param array $capabilities
     * @return array
     */
    public function create($name, array $capabilities)
    {
        $key = str_slug($name, '_');

        $this->option[$key] = $role = [
            'name' => $name,
            'capabilities' => array_merge($this->capabilities, $capabilities),
        ];

        Option::query()->update(['option_name' => $key], [
            'option_value' => serialize($this->option),
        ]);

        return $role;
    }
}
