<?php


namespace DeviceDetector\Helpers;

class DataPacker
{
    private const DELIMITER_DATA = '/([a-z]{2})=([^;]+)?;/i';

    /**
     * pack array to str
     *
     * @param array $data
     * @param array $shortKeyMap
     * @return string
     */
    public static function pack(array $data, array $shortKeyMap): string
    {
        $result = [];
        foreach ($shortKeyMap as $key => $none) {
            $path = $shortKeyMap[$key] ?? null;
            if (null !== $path) {
                $value = ArrayPath::get($data, $shortKeyMap[$key], '');
                $result[] = sprintf('%s=%s;', $key, $value);
            }
        }

        return implode('', $result);
    }

    /**
     * unpack string to array
     *
     * @param string $data
     * @param array $shortKeyMap
     * @return array
     */
    public static function unpack(string $data, array $shortKeyMap): array
    {
        if (!preg_match_all(self::DELIMITER_DATA, $data, $matches)) {
            return [];
        }
        $result = [];
        foreach ($matches as $match) {
            $short = $match[1];
            $value = $match[2] ?? '';
            $path = $shortKeyMap[$short] ?? null;
            if (null !== $path) {
                ArrayPath::set($result, $path, $value);
            }
        }
        return $result;
    }
}