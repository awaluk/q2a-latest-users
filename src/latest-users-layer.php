<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    public function nav_list($navigation, $navtype, $level = null)
    {
        $pages = [
            'users',
            'users/special',
            'users/blocked',
            'users/latest-registered',
            'users/latest-logged'
        ];

        if ($navtype === 'nav-sub' && in_array(qa_request(), $pages)) {
            $latest_registered = (int)qa_opt('latest_registered_users');
            if ($latest_registered > 0) {
                $navigation['users/latest-registered'] = [
                    'label' => qa_lang_html('latest_users/latest_registered_nav'),
                    'url' => qa_path_html('users/latest-registered'),
                    'selected' => qa_request() === 'users/latest-registered'
                ];
            }
            $latest_logged = (int)qa_opt('latest_logged_users');
            if ($latest_logged > 0) {
                $navigation['users/latest-logged'] = [
                    'label' => qa_lang_html('latest_users/latest_logged_nav'),
                    'url' => qa_path_html('users/latest-logged'),
                    'selected' => qa_request() === 'users/latest-logged'
                ];
            }
        }

        parent::nav_list($navigation, $navtype);
    }
}
