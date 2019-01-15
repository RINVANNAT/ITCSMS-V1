<?php

/**
 * Global helpers file with misc functions
 *
 */

if (!function_exists('app_name')) {
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

if (!function_exists('access')) {
    /**
     * Access (lol) the Access:: facade as a simple function
     */
    function access()
    {
        return app('access');
    }
}

if (!function_exists('javascript')) {
    /**
     * Access the javascript helper
     */
    function javascript()
    {
        return app('JavaScript');
    }
}

if (!function_exists('gravatar')) {
    /**
     * Access the gravatar helper
     */
    function gravatar()
    {
        return app('gravatar');
    }
}

if (!function_exists('getFallbackLocale')) {
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

if (!function_exists('getLanguageBlock')) {

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
        $current = $components[0] . "lang." . app()->getLocale() . "." . $components[1];
        $fallback = $components[0] . "lang." . getFallbackLocale() . "." . $components[1];

        if (view()->exists($current)) {
            return view($current, $data);
        } else {
            return view($fallback, $data);
        }
    }
}

if (!function_exists('month_mois')) {
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

if (!function_exists('day_jour')) {
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

if (!function_exists('to_fr_number')) {
    /**
     * Convert english day to french
     *
     * @param $number
     * @return String
     */
    function to_fr_number($number)
    {
        return str_replace(".", ",", $number);
    }
}
if (!function_exists('get_grading')) {
    /**
     * Convert score to grading
     *
     * @param $score
     * @return String
     */
    function get_grading($score, $passedScore = 50)
    {
        $basePassedScore = 50;
        $interval = $basePassedScore - $passedScore;
        $reduceScoreInterval = ($interval) / 9;

        $scoreA = 85 - ($reduceScoreInterval * 3);
        $scoreBPlus = 80 - ($reduceScoreInterval * 1);
        $scoreB = 70 - ($reduceScoreInterval * 1);
        $scoreCPlus = 65 - ($reduceScoreInterval * 1);
        $scoreC = $passedScore;
        $scoreD = $scoreC - 5;
        $scoreE = $scoreD - 5;

        $grade = "";

        if ($score >= $scoreA) {
            $grade = "A";
        } else if ($score >= $scoreBPlus) {
            $grade = "B<sup>+</sup>";
        } else if ($score >= $scoreB) {
            $grade = "B";
        } else if ($score >= $scoreCPlus) {
            $grade = "C<sup>+</sup>";
        } else if ($score >= $scoreC) {
            $grade = "C";
        } else if ($score >= $scoreD) {
            $grade = "D";
        } else if ($score >= $scoreE) {
            $grade = "E";
        } else {
            $grade = "F";
        }
        return $grade;
    }
}
if (!function_exists('get_gpa')) {
    /**
     * Convert score to gpa
     *
     * @param $score
     * @return String
     */
    function get_gpa($score, $passedScore = 50)
    {
        $basePassedScore = 50;
        $interval = $basePassedScore - $passedScore;
        $reduceScoreInterval = ($interval) / 9;

        $scoreA = 85 - ($reduceScoreInterval * 3);
        $scoreBPlus = 80 - ($reduceScoreInterval * 1);
        $scoreB = 70 - ($reduceScoreInterval * 1);
        $scoreCPlus = 65 - ($reduceScoreInterval * 1);
        $scoreC = $passedScore;
        $scoreD = $scoreC - 5;
        $scoreE = $scoreD - 5;

        $gpa = "";

        if ($score >= $scoreA) {
            $gpa = "4.0";
        } else if ($score >= $scoreBPlus) {
            $gpa = "3.5";
        } else if ($score >= $scoreB) {
            $gpa = "3.0";
        } else if ($score >= $scoreCPlus) {
            $gpa = "2.5";
        } else if ($score >= $scoreC) {
            $gpa = "2.0";
        } else if ($score >= $scoreD) {
            $gpa = "1.5";
        } else if ($score >= $scoreE) {
            $gpa = "1.0";
        } else if ($score == "N/A") {
            $gpa = "N/A";
        } else {
            $gpa = "0.00";
        }

        return $gpa;
    }
}

if (!function_exists('get_english_mention')) {
    /**
     * Convert score to mention in english
     *
     * @param $score
     * @return String
     */
    function get_english_mention($score, $passedScore = 50)
    {
        $basePassedScore = 50;
        $interval = $basePassedScore - $passedScore;
        $reduceScoreInterval = ($interval) / 9;

        $scoreA = 85 - ($reduceScoreInterval * 3);
        $scoreBPlus = 80 - ($reduceScoreInterval * 1);
        $scoreB = 70 - ($reduceScoreInterval * 1);
        $scoreCPlus = 65 - ($reduceScoreInterval * 1);
        $scoreC = $passedScore;
        $scoreD = $scoreC - 5;
        $scoreE = $scoreD - 5;

        $mention = "";
        if ($score >= $scoreA) {
            $mention = "Excellent";
        } else if ($score >= $scoreBPlus) {
            $mention = "Very Good";
        } else if ($score >= $scoreB) {
            $mention = "Good";
        } else if ($score >= $scoreCPlus) {
            $mention = "Fairly Good";
        } else if ($score >= $scoreC) {
            $mention = "Fair";
        } else if ($score >= $scoreD) {
            $mention = "Poor";
        } else if ($score >= $scoreE) {
            $mention = "Very Poor";
        } else {
            $mention = "Failure";
        }
        return $mention;
    }
}
if (!function_exists('get_french_mention')) {
    /**
     * Convert score to mention in french
     *
     * @param $score
     * @return String
     */
    function get_french_mention($score, $passedScore = 50)
    {
        $basePassedScore = 50;
        $interval = $basePassedScore - $passedScore;
        $reduceScoreInterval = ($interval) / 9;

        $scoreA = 85 - ($reduceScoreInterval * 3);
        $scoreBPlus = 80 - ($reduceScoreInterval * 1);
        $scoreB = 70 - ($reduceScoreInterval * 1);
        $scoreCPlus = 65 - ($reduceScoreInterval * 1);
        $scoreC = $passedScore;
        $scoreD = $scoreC - 5;
        $scoreE = $scoreD - 5;

        $mention = "";
        if ($score >= $scoreA) {
            $mention = "Excellent";
        } else if ($score >= $scoreBPlus) {
            $mention = "Très Bien";
        } else if ($score >= $scoreB) {
            $mention = "Bien";
        } else if ($score >= $scoreCPlus) {
            $mention = "Assez Bien";
        } else if ($score >= $scoreC) {
            $mention = "Passable";
        } else if ($score >= $scoreD) {
            $mention = "Faible";
        } else if ($score >= $scoreE) {
            $mention = "Très Faible";
        } else {
            $mention = "Insuffisant";
        }
        return $mention;
    }
}
if (!function_exists('to_khmer_number')) {
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
        foreach ($elements as $element) {
            switch ($element) {
                case "0":
                    $kh_number = $kh_number . "០";
                    break;
                case "1":
                    $kh_number = $kh_number . "១";
                    break;
                case "2":
                    $kh_number = $kh_number . "២";
                    break;
                case "3":
                    $kh_number = $kh_number . "៣";
                    break;
                case "4":
                    $kh_number = $kh_number . "៤";
                    break;
                case "5":
                    $kh_number = $kh_number . "៥";
                    break;
                case "6":
                    $kh_number = $kh_number . "៦";
                    break;
                case "7":
                    $kh_number = $kh_number . "៧";
                    break;
                case "8":
                    $kh_number = $kh_number . "៨";
                    break;
                case "9":
                    $kh_number = $kh_number . "៩";
                    break;
                default:
                    break;
            }
        }
        return $kh_number;
    }
}
if (!function_exists('to_khmer_gender')) {
    /**
     * Convert english day to french
     *
     * @param $gender
     * @return String
     */
    function to_khmer_gender($gender)
    {
        $a = strtolower($gender);
        if ($a == "m" || $a == "male") {
            return "ប្រុស";
        } else {
            return "ស្រី";
        }
    }
}
if (!function_exists('to_latin_gender')) {
    /**
     * Convert english day to french
     *
     * @param $gender
     * @return String
     */
    function to_latin_gender($gender)
    {
        $a = strtolower($gender);
        if ($a == "m" || $a == "male") {
            return "Male";
        } else {
            return "Female";
        }
    }
}
if (!function_exists('to_khmer_month')) {
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
                return "កុម្ភៈ";
            case "3":
                return "មីនា";
            case "4":
                return "មេសា";
            case "5":
                return "ឧសភា";
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
                return "វិច្ឆិកា";
            case "12":
                return "ធ្នូ";
            default:
                return "";
        }
    }
}

if (!function_exists('convert_degree')) {
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

function message_success($data)
{
    $response = [
        'code' => 1,
        'data' => $data,
        'message' => 'success message'
    ];
    return $response;
}

function message_error($message)
{
    $response = [
        'code' => 0,
        'data' => [],
        'message' => $message
    ];
    return $response;
}

if (!function_exists('get_order_alpha_numeric')) {
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

if (!function_exists('get_department_option_code')) {
    /**
     * @param null $department_option
     * @return null
     */
    function get_department_option_code($department_option = null)
    {
        if (is_null($department_option)) {
            return null;
        } else {
            return \App\Models\DepartmentOption::find($department_option)->code;
        }
    }
}

if (!function_exists('message_success')) {
    /**
     * @param $data
     * @return array
     */
    function message_success($data)
    {
        return array(
            'code' => 1,
            'status' => true,
            'data' => $data
        );
    }
}

if (!function_exists('message_error')) {
    /**
     * @param $message
     * @return array
     */
    function message_error($message)
    {
        return array(
            'code' => 0,
            'status' => false,
            'message' => $message
        );
    }
}

if (!function_exists('sort_groups')) {
    function sort_groups(array $groups)
    {
        usort($groups, function ($a, $b) {
            if (is_numeric($a['code']) && !is_numeric($b['code'])) {
                return 1;
            } else if (!is_numeric($a['code']) && is_numeric($b['code'])) {
                return -1;
            } else {
                return ($a['code'] < $b['code']) ? -1 : 1;
            }
        });
    }
}

if (!function_exists('durations')) {
    function durations(\Carbon\Carbon $start, \Carbon\Carbon $end)
    {
        $start = \Carbon\Carbon::parse($start);
        $end = \Carbon\Carbon::parse($end);
        if (($end->minute > 0 && $start->minute == 0) || ($end->minute == 0 && $start->minute > 0)) {
            return $start->diffInHours($end) + 0.5;
        } else {
            return $start->diffInHours($end);
        }
    }
}