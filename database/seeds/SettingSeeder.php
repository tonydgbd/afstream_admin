<?php

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        DB::table('settings')->insert([
            0 => [
                'name' => 'w_title',
                'value' => 'Musioo',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            1 => [
                'name' => 'logo',
                'value' => 'logo.png',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            2 => [
                'name' => 'favicon',
                'value' => 'favicon.png',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            3 => [
                'name' => 'preloader',
                'value' => 'loader.gif',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            4 => [
                'name' => 'meta_desc',
                'value' => 'Musioo laravel admin dashboard template',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            5 => [
                'name' => 'keywords',
                'value' => 'music, song, fun, entertainment',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            6 => [
                'name' => 'author_name',
                'value' => 'kamlesh yadav',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            7 => [
                'name' => 'default_currency_id',
                'value' => '1',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            8 => [
                'name' => 'is_preloader',
                'value' => '1',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            9 => [
                'name' => 'is_gotop',
                'value' => '1',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            10 => [
                'name' => 'inspect',
                'value' => '1',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            11 => [
                'name' => 'right_click',
                'value' => '1',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            12 => [
                'name' => 'wel_mail',
                'value' => '1',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            13 => [
                'name' => 'is_footer',
                'value' => '1',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            14 => [
                'name' => 'section_1_heading',
                'value' => 'Musioo Music Station',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            15 => [
                'name' => 'section_1_description',
                'value' => 'The Musioo – Online Music Store Script is packed with many features that allow you to play music while browsing other pages.',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            16 => [
                'name' => 'section_2_heading',
                'value' => 'Download Our App',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            17 => [
                'name' => 'google_play_url',
                'value' => '#',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            18 => [
                'name' => 'app_store_url',
                'value' => '#',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            19 => [
                'name' => 'window_store_url',
                'value' => '#',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            20 => [
                'name' => 'section_3_heading',
                'value' => 'Subscribe',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            21 => [
                'name' => 'section_3_description',
                'value' => 'Subscribe to our newsletter and get latest updates and offers.',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            22 => [
                'name' => 'section_4_heading',
                'value' => 'Contact Us',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            23 => [
                'name' => 'w_email',
                'value' => 'info@musioo.com',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            24 => [
                'name' => 'w_phone',
                'value' => '(+1) 202-555-0176',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            25 => [
                'name' => 'w_address',
                'value' => '598 Old House Drive London',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            26 => [
                'name' => 'facebook_url',
                'value' => 'https://facebook.com/',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            27 => [
                'name' => 'linkedin_url',
                'value' => 'https://linkedin.com/',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            28 => [
                'name' => 'twitter_url',
                'value' => 'https://twitter.com/',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            29 => [
                'name' => 'google_plus_url',
                'value' => 'https://plus.google.com/',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            30 => [
                'name' => 'copyrightText',
                'value' => '© Copyright 2021, All Rights Reserved Musioo',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            31 => [
                'name' => 'MAILCHIMP_APIKEY',
                'value' => 'c08ffa2fee117d71d4f2b2c1bd41e675-us4',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            32 => [
                'name' => 'is_newsletter',
                'value' => '1',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            33 => [
                'name' => 'is_header_msg',
                'value' => '1',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            34 => [
                'name' => 'header_title',
                'value' => 'Trending Songs',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            35 => [
                'name' => 'header_description',
                'value' => 'Dream your moments, Until I Met You, Gimme Some Courage, Dark Alley',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            36 => [
                'name' => 'mini_logo',
                'value' => 'mini_logo.png',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ],
            37 => [
                'name' => 'large_logo',
                'value' => 'large_logo.png',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            ]
        ]);
    }
}
