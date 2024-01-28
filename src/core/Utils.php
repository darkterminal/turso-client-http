<?php

namespace Darkterminal\TursoHttp\core;

/**
 * Class Utils
 *
 * Utility class containing various static methods for common tasks.
 */
final class Utils
{
    /**
     * Get API endpoint configuration.
     *
     * @param string $type The type of the API.
     * @param string $action The action to perform on the API.
     *
     * @return array The endpoint configuration for the specified type and action.
     *
     * @throws \Exception Throws an exception with a 403 HTTP response code if the endpoint configuration is not found.
     */
    public static function useAPI($type, $action): array
    {
        $endpoints = require 'Repositories'. \DIRECTORY_SEPARATOR .'endpoints.php';

        if (isset($endpoints[$type][$action])) {
            return $endpoints[$type][$action];
        } else {
            \http_response_code(403);
            throw new \Exception("Endpoint configuration not found for $type/$action");
        }
    }

    /**
     * Validate a member role.
     *
     * @param string $roleName The name of the member role.
     *
     * @throws \Exception Throws an exception with a 403 HTTP response code if the role is not valid.
     */
    public static function validateMemberRole(string $roleName): void
    {
        $roles = ['owner','admin','member'];
        if (!in_array($roleName, $roles)) {
            \http_response_code(403);
            throw new \Exception("The role is not valid, role options: owner, admin, or member");
        }
    }

    /**
     * Validate a user role.
     *
     * @param string $roleName The name of the user role.
     *
     * @throws \Exception Throws an exception with a 403 HTTP response code if the role is not valid.
     */
    public static function validateUserRole(string $roleName): void
    {
        $roles = ['admin','member'];
        if (!in_array($roleName, $roles)) {
            \http_response_code(403);
            throw new \Exception("The role is not valid, role options: owner, admin, or member");
        }
    }

    /**
     * Perform a cURL request.
     *
     * @param string $method The HTTP request method (e.g., "GET", "POST").
     * @param string $url The URL to send the request to.
     * @param string $authToken The token to send the request to.
     * @param array $data Optional. The data to be sent with the request (used in POST and PUT requests).
     *
     * @return mixed Returns the decoded JSON response as an associative array on success, or a string containing the cURL error on failure.
     */
    public static function makeRequest(string $method, string $url, string $authToken, array $data = []): mixed
    {
        $headers = [
            'Authorization: Bearer ' . $authToken,
            'Content-Type: application/json',
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        if ($method === 'POST' || $method === 'PUT') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error: " . $err;
        } else {
            return json_decode($response, true);
        }
    }

    /**
     * Upload a file using cURL.
     *
     * @param string $url The URL to upload the file to.
     * @param string $token The authentication token.
     * @param string $filePath The path to the file to be uploaded.
     *
     * @return mixed Returns the decoded JSON response as an associative array on success, or a string containing the cURL error on failure.
     */
    public static function uploadDump(string $url, string $token, string $filePath): mixed
    {
        $headers = [
            'Authorization: Bearer ' . $token,
        ];

        $postData = [
            'file' => new \CURLFile($filePath),
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $response = curl_exec($ch);
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            return "Error: $error";
        } else {
            return \json_decode($response, true);
        }
    }

    /**
     * Get the closest region using a cURL request.
     *
     * @param string $token The authentication token.
     *
     * @return array Returns the closest region information as an associative array.
     */
    public static function closestRegion(string $token): array
    {
        return self::makeRequest('GET', 'https://region.turso.io', $token);
    }
}
