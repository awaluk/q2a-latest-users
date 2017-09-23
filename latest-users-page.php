<?php
/**
 * Q2A Latest users - plugin to Question2Answer
 * @author Arkadiusz Waluk <arkadiusz@waluk.pl>
 */

class latest_users_page
{
    public function match_request($request)
    {
        $latest_registered = (int) qa_opt('latest_registered_users');
        $latest_logged = (int) qa_opt('latest_logged_users');
        return ($request === 'users/latest-registered' && $latest_registered > 0)
            || ($request === 'users/latest-logged' && $latest_logged > 0);
    }

    public function process_request($request)
    {
        $latest_registered = (int) qa_opt('latest_registered_users');
        $latest_logged = (int) qa_opt('latest_logged_users');
        $qa_content = qa_content_prepare();

        if (qa_user_level_maximum() < QA_USER_LEVEL_MODERATOR) {
            $qa_content['error'] = qa_lang_html('latest_users/no_permission');
        } else {
            $qa_content['navigation']['sub'] = qa_users_sub_navigation();
            $qa_content['head_lines']['latest_users'] = '<style>
                .latest-table {
                    width: 100%;
                }
                .lastest-center {
                    text-align: center;
                }

                .duplicated-ip,
                .duplicated-ip:hover,
                .duplicated-ip:visited {
                    color: #FF0000;
                }

            </style>';

            $sql = '';
            if ($request === 'users/latest-registered' && $latest_registered > 0) {
                $qa_content['title'] = qa_lang_html('latest_users/latest_registered_title');
                $sql = 'SELECT userid, created, createip, flags, email, handle, avatarblobid, avatarwidth, avatarheight
                FROM ^users ORDER BY created DESC LIMIT #';

                $limit = $latest_registered;
            } elseif ($request === 'users/latest-logged' && $latest_logged > 0) {
                $qa_content['title'] = qa_lang_html('latest_users/latest_logged_title');
                $sql = 'SELECT userid, loggedin, loginip, flags, email, handle, avatarblobid, avatarwidth, avatarheight
                FROM ^users ORDER BY loggedin DESC LIMIT #';

                $limit = $latest_logged;
            }

            if (!empty($sql)) {
                $users = qa_db_read_all_assoc(qa_db_query_sub($sql, $limit));
                $usershtml = qa_userids_handles_html($users);

                $user_array_ip_key = '';

                if ($request === 'users/latest-registered') {
                    $user_array_ip_key = 'createip';
                } elseif ($request === 'users/latest-logged') {
                    $user_array_ip_key = 'loginip';
                }

                $duplicated_ips = $this->get_duplicates(array_column($users, $user_array_ip_key));

                $usersrows = '';
                foreach ($users as $user) {
                    if (QA_FINAL_EXTERNAL_USERS) {
                        $avatarhtml = qa_get_external_avatar_html($user['userid'], qa_opt('avatar_users_size'), true);
                    } else {
                        $avatarhtml = qa_get_user_avatar_html($user['flags'], $user['email'], $user['handle'], $user['avatarblobid'], $user['avatarwidth'], $user['avatarheight'], qa_opt('avatar_users_size'), true);
                    }

                    $ip_link_style = '';

                    foreach ($duplicated_ips as $duplicate) {
                        if ($user[$user_array_ip_key] === $duplicate) {
                            $ip_link_style = 'duplicated-ip';
                        }
                    }

                    $ip = long2ip($user[$user_array_ip_key]);
                    $ip_html = $this->get_ip_html($ip, $ip_link_style);

                    $usersrows .= '<tr>
                        <td class="qa-top-users-label">' . $avatarhtml . $usershtml[$user['userid']] . '</td>
                        <td class="lastest-center">' . ($request === 'users/latest-registered' ? $user['created'] : $user['loggedin']) . '</td>
                        <td class="lastest-center">' . $ip_html . '</td>
                    </tr>';
                }

                $qa_content['custom'] = '<table class="latest-table">
                    <tr class="lastest-center">
                        <th>' . qa_lang_html('latest_users/user') . '</th>
                        <th>' . qa_lang_html('latest_users/date') . '</th>
                        <th>' . qa_lang_html('latest_users/ip') . '</th>
                    </tr>
                    ' . $usersrows . '
                </table>';
            }
        }
        return $qa_content;
    }

    function admin_form()
    {
        $saved = false;
        if (qa_clicked('latest_users_save')) {
            $latest_registered = (int) qa_post_text('latest_registered_users');
            $latest_logged = (int) qa_post_text('latest_logged_users');

            qa_opt('latest_registered_users', $latest_registered);
            qa_opt('latest_logged_users', $latest_logged);
            $saved = true;
        }
        $form = [
            'ok' => $saved ? qa_lang_html('latest_users/admin_ok_info') : null,
            'fields' => [
                'input1' => [
                    'type' => 'number',
                    'label' => qa_lang_html('latest_users/latest_registered_admin') . ':',
                    'value' => qa_html(qa_opt('latest_registered_users')),
                    'tags' => 'name="latest_registered_users"'
                ],
                'input2' => [
                    'type' => 'number',
                    'label' => qa_lang_html('latest_users/latest_logged_admin') . ':',
                    'value' => qa_html(qa_opt('latest_logged_users')),
                    'tags' => 'name="latest_logged_users"'
                ],
                'zero_description' => [
                    'label' => '<i>' . qa_lang_html('latest_users/admin_zero_info') . '</i>',
                    'type' => 'static'
                ]
            ],
            'buttons' => [
                [
                    'label' => qa_lang_html('latest_users/admin_save_button'),
                    'tags' => 'name="latest_users_save"'
                ]
            ]
        ];

        return $form;
    }

    private function get_duplicates($array)
    {
        $unique_values = array_unique($array);
        $duplicate_values = array_diff_assoc($array, $unique_values);

        return array_unique($duplicate_values);
    }

    private function get_ip_html($ip, $link_style = '')
    {
        $href = qa_path_html('ip/' . $ip);
        $title = qa_lang_html_sub('main/ip_address_x', qa_html($ip));

        $html = '<a href="' . $href . '" title="' . $title . '" class="qa-ip-link ' . $link_style . '">' . $ip . '</a>';
        return $html;
    }
}
