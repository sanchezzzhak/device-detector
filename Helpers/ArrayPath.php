<?php


namespace DeviceDetector\Helpers;


class ArrayPath
{
    /**
     * array getter
     *
     * @param array $array
     * @param $key
     * @param null $default
     * @return mixed|null
     * ```
     * $osVersion = ArrayPath::get($device, 'os.version');
     * // or
     * $value = ArrayPath::get($versions, ['1.0', 'date']);
     * ```
     */
    public static function get(array $array, $key, $default = null)
    {
        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = self::get($array, $keyPart);
            }
            $key = $lastKey;
        }
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }
        if ($key && ($pos = strrpos($key, '.')) !== false) {
            $array = self::get($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }
        return $default;
    }

    /**
     * array setter
     *
     * @param array $data
     * @param $path
     * @param $value
     * ```
     * ArrayPath::set($array, 'key.in', ['arr' => 'val']);
     * // or
     * ArrayPath::set($array, ['key', 'in'], ['arr' => 'val']);
     * // or
     * ArrayPath::set($array, 'key', 'val');
     * ```
     */
    public static function set(array &$data, $path, $value): void
    {
        if ($path === null) {
            $data = $value;
            return;
        }
        $keys = is_array($path) ? $path : explode('.', $path);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($data[$key])) {
                $data[$key] = [];
            }
            if (!is_array($data[$key])) {
                $data[$key] = [$data[$key]];
            }
            $data = &$data[$key];
        }
        $data[array_shift($keys)] = $value;
    }

    public static function map($array, $from, $to, $group = null): array
    {
        $result = [];
        foreach ($array as $item) {
            $key = self::get($item, $from);
            $value = self::get($item, $to);
            if ($group !== null) {
                $result[self::get($item, $group)][$key] = $value;
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

}