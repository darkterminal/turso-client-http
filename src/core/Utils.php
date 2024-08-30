<?php

namespace Darkterminal\TursoHttp\core;

use Darkterminal\TursoHttp\core\Enums\HttpResponse;
use Darkterminal\TursoHttp\core\Repositories\Endpoints;
use Exception;

/**
 * Class Utils
 *
 * Utility class containing various static methods for common tasks.
 */
final class Utils
{
    /**
     * Perform a cURL request.
     *
     * @param string $method The HTTP request method (e.g., "GET", "POST").
     * @param string $url The URL to send the request to.
     * @param string|null $authToken The token to send the request to.
     * @param array $data Optional. The data to be sent with the request (used in POST and PUT requests).
     *
     * @return mixed Returns the decoded JSON response as an associative array on success, or a string containing the cURL error on failure.
     */
    public static function makeRequest(string $method, string $url, string|null $authToken, array $data = []): string|array
    {
        $headers = [
            "Content-Type: application/json",
        ];

        if (!is_null($authToken)) {
            $headers[] = "Authorization: Bearer $authToken";
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        if (($method === 'POST' || $method === 'PUT' || $method === 'PATCH') && !empty($data)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return "cURL Error: $err";
        } else {
            return self::isJson($response) ? json_decode($response, true) : $response;
        }
    }

    /**
     * Parses a DSN string and returns an associative array of its components.
     *
     * @param string $dsn
     * @return array
     */
    public static function parseDsn(string $dsn): array
    {
        $result = [];
        $components = preg_split('/[&;]/', $dsn);

        if (isLocalDev($components)) {
            $result['dbname'] = $components[0];
            return $result;
        }

        foreach ($components as $component) {
            $parts = explode('=', $component, 2);
            if (count($parts) === 2) {
                $result[$parts[0]] = $parts[1];
            }
        }

        return $result;
    }

    public static function isJson(string $string): bool
    {
        if (function_exists('json_validate')) {
            return json_validate($string);
        }

        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Removes objects from the results array where response.type is "close".
     *
     * @param array $data The input array.
     * @return array The modified array.
     */
    public static function removeCloseResponses(array $data): array
    {
        if (isset($data) && is_array($data)) {
            $data = array_filter($data, function ($result) {
                return !(
                    isset($result['type']) && $result['type'] === 'ok' &&
                    isset($result['response']['type']) && $result['response']['type'] === 'close'
                );
            });
            // Re-index the array
            $data = array_values($data);
        }

        if ($data[0]['type'] === 'error') {
            throw new LibSQLError($data[0]['error']['message'], $data[0]['error']['code']);
        }
        return $data[0]['response']['result'];
    }

    /**
     * Checks if an array is associative.
     *
     * @param array $array
     * @return bool
     */
    public static function isArrayAssoc(array $array): bool
    {
        if ([] === $array)
            return false;
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * Creates the directory and file for a given path if they don't exist.
     *
     * @param string $relativePath The relative path for the file.
     * @param bool $createFile Whether to create the file if it doesn't exist.
     * @return void
     */
    public static function createDirectoryAndFile(string $relativePath, bool $createFile = true): void
    {
        $directory = dirname($relativePath);

        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true)) {
                die("Failed to create directory: $directory");
            }
        }

        if ($createFile && !file_exists($relativePath)) {
            if (!touch($relativePath)) {
                die("Failed to create file: $relativePath");
            }
        }
    }

    /**
     * Extracts the user home directory from a given path by retrieving the first and second path segments.
     *
     * @return string|null The user home directory or null if the path is invalid.
     */
    public static function getUserHomeDirectory(): ?string
    {
        $normalizedPath = preg_replace('#/+#', '/', __DIR__);
        $pathSegments = explode('/', trim($normalizedPath, '/'));

        if (count($pathSegments) >= 2) {
            return "/{$pathSegments[0]}/{$pathSegments[1]}";
        }

        return null;
    }
}
