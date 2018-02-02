<?php

/**
 * Global helpers file with misc functions
 *
 */

if (! function_exists('app_name')) {
    /**
     * Helper to grab the application name
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (! function_exists('access')) {
    /**
     * Access (lol) the Access:: facade as a simple function
     */
    function access()
    {
        return app('access');
    }
}

if (! function_exists('javascript')) {
    /**
     * Access the javascript helper
     */
    function javascript()
    {
        return app('JavaScript');
    }
}

if (! function_exists('gravatar')) {
    /**
     * Access the gravatar helper
     */
    function gravatar()
    {
        return app('gravatar');
    }
}

if (! function_exists('getFallbackLocale')) {
    /**
     * Get the fallback locale
     *
     * @return \Illuminate\Foundation\Application|mixed
     */
    function getFallbackLocale()
    {
        return config('app.fallback_locale');
    }
}

if (! function_exists('getLanguageBlock')) {

    /**
     * Get the language block with a fallback
     *
     * @param $view
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function getLanguageBlock($view, $data = [])
    {
        $components = explode("lang", $view);
        $current  = $components[0]."lang.".app()->getLocale().".".$components[1];
        $fallback  = $components[0]."lang.".getFallbackLocale().".".$components[1];

        if (view()->exists($current)) {
            return view($current, $data);
        } else {
            return view($fallback, $data);
        }
    }
}

if (! function_exists('month_mois')) {
    /**
     * Convert english month to french
     *
     * @param $month
     * @return String
     */
    function month_mois($month)
    {
        $month = strtolower($month);
        switch ($month) {
            case "january":
                return "Janvier";
            case "february":
                return "Février";
            case "march":
                return "Mars";
            case "april":
                return "Avril";
            case "may":
                return "Mai";
            case "june":
                return "Juin";
            case "july":
                return "Juillet";
            case "august":
                return "Août";
            case "september":
                return "Septembre";
            case "october":
                return "Octobre";
            case "november":
                return "Novembre";
            case "december":
                return "Décembre";
            default:
                return null;
        }
    }
}

if (! function_exists('day_jour')) {
    /**
     * Convert english day to french
     *
     * @param $day
     * @return String
     */
    function day_jour($day)
    {
        $day = strtolower($day);
        switch ($day) {
            case "monday":
                return "Lundi";
            case "tuesday":
                return "Mardi";
            case "wednesday":
                return "Mercredi";
            case "thursday":
                return "Jeudi";
            case "friday":
                return "Vendredi";
            case "saturday":
                return "Samedi";
            case "sunday":
                return "Dimanche";
            default:
                return null;
        }
    }
}

if (! function_exists('to_fr_number')) {
    /**
     * Convert english day to french
     *
     * @param $number
     * @return String
     */
    function to_fr_number($number)
    {
        return str_replace(".",",",$number);
    }
}
if (! function_exists('get_grading')) {
    /**
     * Convert english day to french
     *
     * @param $score
     * @return String
     */
    function get_grading($score)
    {
        $grade = "";
        if($score >= 85){
            $grade = "A";
        } else if ($score >= 80) {
            $grade = "B<sup>+</sup>";
        } else if ($score >= 70) {
            $grade = "B";
        } else if ($score >= 65) {
            $grade = "C<sup>+</sup>";
        } else if ($score >= 50) {
            $grade = "C";
        } else if ($score >= 45) {
            $grade = "D";
        } else if ($score >= 40) {
            $grade = "E";
        }  else {
            $grade = "F";
        }
        return $grade;
    }
}
if (! function_exists('to_khmer_number')) {
    /**
     * Convert english day to french
     *
     * @param $number
     * @return String
     */
    function to_khmer_number($number)
    {
        $elements = trim($number);
        $elements = str_split($elements);
        $kh_number = "";
        foreach($elements as $element) {
            switch ($element) {
                case "0":
                    $kh_number = $kh_number."០";
                    break;
                case "1":
                    $kh_number = $kh_number."១";
                    break;
                case "2":
                    $kh_number = $kh_number."២";
                    break;
                case "3":
                    $kh_number = $kh_number."៣";
                    break;
                case "4":
                    $kh_number = $kh_number."៤";
                    break;
                case "5":
                    $kh_number = $kh_number."៥";
                    break;
                case "6":
                    $kh_number = $kh_number."៦";
                    break;
                case "7":
                    $kh_number = $kh_number."៧";
                    break;
                case "8":
                    $kh_number = $kh_number."៨";
                    break;
                case "9":
                    $kh_number = $kh_number."៩";
                    break;
                default:
                    break;
            }
        }
        return $kh_number;
    }
}
if (! function_exists('to_khmer_gender')) {
    /**
     * Convert english day to french
     *
     * @param $gender
     * @return String
     */
    function to_khmer_gender($gender)
    {
        $a = strtolower($gender);
        if($a == "m" || $a == "male") {
            return "ប្រុស";
        } else {
            return "ស្រី";
        }
    }
}
if (! function_exists('to_khmer_month')) {
    /**
     * Convert english day to french
     *
     * @param $month
     * @return String
     */
    function to_khmer_month($month)
    {
        switch ($month) {
            case "1":
                return "មករា";
            case "2":
                return "កុម្ភះ";
            case "3":
                return "មិនា";
            case "4":
                return "មេសា";
            case "5":
                return "ឪសភា";
            case "6":
                return "មិថុនា";
            case "7":
                return "កក្កដា";
            case "8":
                return "សីហា";
            case "9":
                return "កញ្ញា";
            case "10":
                return "តុលា";
            case "11":
                return "វិច្ឆកា";
            case "12":
                return "ធ្នូ";
            default:
                return "";
        }
    }
}
if (! function_exists('ddc')) {
    /**
     * Debugs code.
     *
     * @param $data
     */
    function ddc($data)
    {
        echo json_encode($data);
        die();
    }
}