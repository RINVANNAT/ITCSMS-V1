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