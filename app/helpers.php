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
     * Convert score to grading
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
if (! function_exists('get_gpa')) {
    /**
     * Convert score to gpa
     *
     * @param $score
     * @return String
     */
    function get_gpa($score)
    {
        $gpa = "";
        if($score >= 85){
            $gpa = "4.00";
        } else if ($score >= 80) {
            $gpa = "3.50";
        } else if ($score >= 70) {
            $gpa = "3.00";
        } else if ($score >= 65) {
            $gpa = "2.50";
        } else if ($score >= 50) {
            $gpa = "2.00";
        } else if ($score >= 45) {
            $gpa = "1.50";
        } else if ($score >= 40) {
            $gpa = "1.00";
        }  else if ($score == "N/A") {
            $gpa = "N/A";
        } else {
            $gpa = "0.00";
        }
        return $gpa;
    }
}
if (! function_exists('get_english_mention')) {
    /**
     * Convert score to mention in english
     *
     * @param $score
     * @return String
     */
    function get_english_mention($score)
    {
        $mention = "";
        if($score >= 85){
            $mention = "Excellent";
        } else if ($score >= 80) {
            $mention = "Very Good";
        } else if ($score >= 70) {
            $mention = "Good";
        } else if ($score >= 65) {
            $mention = "Fairly Good";
        } else if ($score >= 50) {
            $mention = "Fair";
        } else if ($score >= 45) {
            $mention = "Poor";
        } else if ($score >= 40) {
            $mention = "Very Poor";
        }  else {
            $mention = "Failure";
        }
        return $mention;
    }
}
if (! function_exists('get_french_mention')) {
    /**
     * Convert score to mention in french
     *
     * @param $score
     * @return String
     */
    function get_french_mention($score)
    {
        $mention = "";
        if($score >= 85){
            $mention = "Excellent";
        } else if ($score >= 80) {
            $mention = "Très Bien";
        } else if ($score >= 70) {
            $mention = "Bien";
        } else if ($score >= 65) {
            $mention = "Assez Bien";
        } else if ($score >= 50) {
            $mention = "Passable";
        } else if ($score >= 45) {
            $mention = "Faible";
        } else if ($score >= 40) {
            $mention = "Très Faible";
        }  else {
            $mention = "Insuffisant";
        }
        return $mention;
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
if (! function_exists('to_latin_gender')) {
    /**
     * Convert english day to french
     *
     * @param $gender
     * @return String
     */
    function to_latin_gender($gender)
    {
        $a = strtolower($gender);
        if($a == "m" || $a == "male") {
            return "Male";
        } else {
            return "Female";
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

if (! function_exists('convert_degree')) {
    /**
     * Convert string number to day
     *
     * @param int $year
     * @return string
     */
    function convert_degree($year)
    {
        switch ($year) {
            case 1:
                return "1st";
            case 2:
                return "2nd";
            case 3:
                return "3rd";
            case 4:
                return "4th";
            case 5:
                return "5th";
            default:
                return "";
        }
    }
}

function message_success ($data) {
    $response = [
        'code' => 1,
        'data' => $data,
        'message' => 'success message'
    ];
    return $response;
}

function message_error ($message) {
    $response = [
        'code' => 0,
        'data' => [],
        'message' => $message
    ];
    return $response;
}

if (! function_exists('get_order_alpha_numeric')) {
    /**
     * Convert number to alpha numeric number
     *
     * @param int $number
     * @return string
     */
    function get_order_alpha_numeric($number)
    {
        switch ($number) {
            case 1:
                return "First";
            case 2:
                return "Second";
            case 3:
                return "Third";
            case 4:
                return "Forth";
            case 5:
                return "Fifth";
            default:
                return "";
        }
    }
}