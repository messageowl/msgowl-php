<?php

namespace MessageOwl\Resources;

use MessageOwl\Config;
use MessageOwl\Http\HttpClient;
use MessageOwl\Responses\OtpResponse;
use MessageOwl\Responses\OtpVerifyResponse;

class Otp
{
    public function __construct(private readonly HttpClient $http)
    {
    }

    public function send(
        string $phone,
        ?string $code = null,
        ?int $codeLength = null,
        ?string $timestamp = null,
        ?string $appHash = null,
    ): OtpResponse {
        $body = ['phone_number' => $phone];

        if ($code !== null) {
            $body['code'] = $code;
        }

        if ($codeLength !== null) {
            $body['code_length'] = $codeLength;
        }

        if ($timestamp !== null) {
            $body['timestamp'] = $timestamp;
        }

        if ($appHash !== null) {
            $body['app_hash'] = $appHash;
        }

        $data = $this->http->post(Config::OTP_BASE_URL . '/send', $body);

        return OtpResponse::fromArray($data);
    }

    public function resend(string $phone, int $id, ?string $appHash = null): OtpResponse
    {
        $body = [
            'phone_number' => $phone,
            'id'           => $id,
        ];

        if ($appHash !== null) {
            $body['app_hash'] = $appHash;
        }

        $data = $this->http->post(Config::OTP_BASE_URL . '/resend', $body);

        return OtpResponse::fromArray($data);
    }

    public function verify(string $phone, string $code): OtpVerifyResponse
    {
        $data = $this->http->post(Config::OTP_BASE_URL . '/verify', [
            'phone_number' => $phone,
            'code'         => $code,
        ]);

        return OtpVerifyResponse::fromArray($data);
    }
}
