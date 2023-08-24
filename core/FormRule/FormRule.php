<?php

namespace core\FormRule;

class FormRule
{
    private array $regRules
        = [
            'phone'    => '/^1[3-9]\d{9}$/',
            'int'      => '/^-?[0-9]+$/',
            'float'    => '/^-?[0-9]+\.?([0-9]*)?$/',
            'length'   => '/^.{$1,$2}$/',
            'required' => '/\S+/',
        ];

    public string $errorMessage = '';


    public function validate(array $data, array $rules, array $messages = [])
    {
        foreach ($rules as $key => $rule) {
            if (is_string($rule)) {
                $rule = explode("|", $rule);
            }
            if ( ! is_array($rule)) {
                throw new \Exception('error');
            }

            foreach ($rule as $item) {
                if (empty($item)) {
                    continue;
                }
                $arrRule = explode(":", $item);
                $item    = $arrRule[0];
                if ( ! isset($this->regRules[$item])) {
                    throw new \Exception('error');
                }

                $length = count($arrRule);
                if ($length == 2) {
                    $tags   = explode(",", $arrRule[1]);
                    $length = count($tags);

                    $rul = $this->regRules[$item];
                    for ($i = 0; $i < $length; $i++) {
                        $index = $i + 1;
                        $rul   = str_replace("\${$index}", $tags[$i], $rul);
                    }
                } else {
                    $rul = $this->regRules[$item];
                }

                if (!isset($data[$key])){
                    $this->errorMessage = "{$key} is not empty";
                    return false;
                }

                $is = (bool)preg_match($rul, $data[$key]);
                if ( ! $is) {
                    if (isset($messages[$key.".".$item])) {
                        $this->errorMessage = $messages[$key.".".$item];
                    } else {
                        $this->errorMessage = "{$key} 驗證失敗";
                    }

                    return false;
                }
            }
        }

        return true;
    }


    public static function gt(){}

    public static function lt(){}

    public static function eq(){}

    public static function in(){}


}