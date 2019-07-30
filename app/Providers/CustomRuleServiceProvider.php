<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Validator;

class CustomRuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Validator::extend('check_email', function ($attribute, $value) {
            return preg_match("/^[a-zA-Z0-9.!#$%&'*+\/=? ^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/", $value);
        });

        Validator::extend('check_url', function ($attribute, $value) {
            return preg_match("/^(https:\/\/|http:\/\/)([^ぁ-んァ-ヶ　ａ-ｚＡ-Ｚ０-９ー・]+)$/", $value);
        });

        Validator::extend('check_url_if', function ($attribute, $value, $parameters, $validator) {
            $valueCheck = array_get($validator->getData(), $parameters[0]);
            if ($parameters[1] == "!=") {
                if ($valueCheck != $parameters[2]) {
                    return preg_match("/^(https:\/\/|http:\/\/)([^ぁ-んァ-ヶ　ａ-ｚＡ-Ｚ０-９ー・]+)$/", $value);
                }
            } else {
                if ($valueCheck == $parameters[1] || isset($parameters[2]) && $valueCheck == $parameters[2]) {
                    return preg_match("/^(https:\/\/|http:\/\/)([^ぁ-んァ-ヶ　ａ-ｚＡ-Ｚ０-９ー・]+)$/", $value);
                }
            }

            return true;
        });

        Validator::extend('is_kana', function ($attribute, $value, $parameters, $validator) {
            $kana_space = '/^[｡-ﾟ 　ァ-ヶー・]+$/u';

            // all characters is space
            if (preg_match("/^[ 　]+$/u", $value)) {
                return false;
            }

            if (preg_match($kana_space, $value)) {
                return true;
            }

            return false;
        });

        Validator::extend('furigana_and_space', function ($attribute, $value, $parameters, $validator) {
            $kana_space = '/^[｡-ﾟ 　ァ-ヶー・]+$/u';
            $hiragana = '/^[ぁ-ん 　]+$/u';

            if (preg_match("/^[ 　]+$/u", $value)) {
                return false;
            }

            if (preg_match($hiragana, $value) || preg_match($kana_space, $value)) {
                return true;
            }
            return false;
        });

        Validator::extend('check_date', function ($attribute, $value) {
            if ($value) {
                $date = explode('-', $value);

                return checkdate($date[1], $date[2], $date[0]);
            }

            return false;
        });

        Validator::extend('valid_date', function ($attribute, $value, $parameters, $validator) {
            $params = $validator->getData();
            if (!checkdate($params['month'], $params['day'], $params['year'])) {
                return false;
            }

            return true ;
        });

        Validator::extend('check_format_date', function ($attribute, $value) {
            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $value)) {
                return checkdate((int)substr($value, 5, 2), (int)substr($value, 8, 2), (int)substr($value, 0, 4));
            }

            return false;
        });

        // # check for matches number-alphabets and commas only
        Validator::extend('check_half_size_number', function ($attribute, $value, $parameters, $validator) {
            if (preg_match("/^[a-zA-Z0-9,]+$/", $value)) {
                return true;
            }

            return false;
        });

        Validator::extend('strong_password', function ($attribute, $value, $parameters, $validator) {
            $pattern = '/^.*((?=.*[!@#$%^&*()\-_ =+{};:,<.>]){1})(?=.*\d)((?=.*[a-z]){1})((?=.*[A-Z]){1}).*$/';
            return preg_match($pattern, $value);
        });

        Validator::extend('password_required_char', function ($attribute, $value, $parameters, $validator) {
            $pattern = '/^.*(?=.*\d)((?=.*[a-z]){1})((?=.*[A-Z]){1}).*$/';
            return preg_match($pattern, $value);
        });

        Validator::extend('password_format', function ($attribute, $value, $parameters, $validator) {
            $pattern = '/^[a-zA-Z0-9\'@`!" #$%&\'()*+,-.\/:;[{<\|=\]}>\^~? _]*$/';
            return preg_match($pattern, $value);
        });

        Validator::extend('required_date', function ($attribute, $value, $parameters, $validator) {
            $paramOne = Arr::get($validator->getData(), $parameters[0]);
            $paramTwo = Arr::get($validator->getData(), $parameters[1]);

            return (empty($paramOne) || empty($paramTwo)) ? false : true ;
        });

        Validator::extend('is_phone', function ($attribute, $value) {
            $pattern = '/^[+]?([(]{1}[+]{0,1}[0-9]{1,4}[)]{1})?[0-9-. ]+$/';
            return preg_match($pattern, $value);
        });
    }

    public function register()
    {
        //
    }
}
