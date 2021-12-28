<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $options = [
            [
                'option_name' => 'site_name', 
                'option_value' => 'LinaMart',
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'tag_line', 
                'option_value' => 'No 1 marketplace ever',
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'site_description', 
                'option_value' => 'Number 1 marketplace ever',
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'admin_email', 
                'option_value' => 'farid@localhost.com',
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'theme', 
                'option_value' => 'default',
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'visitor_tracking', 
                'option_value' => 0,
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'extensions', 
                'option_value' => json_encode([]),
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'language', 
                'option_value' => 'id',
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'timezone', 
                'option_value' => 'Asia/Jakarta',
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'meta_robots', 
                'option_value' => 'noindex',
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'company_name', 
                'option_value' => 'MemberShop',
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'company_email', 
                'option_value' => 'contact@localhost.com',
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'company_logo', 
                'option_value' => '',
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'company_address', 
                'option_value' => 'Sleman, Daerah Istimewa Yogyakarta',
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'company_phone', 
                'option_value' => '0274-123456',
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'admin_phone', 
                'option_value' => '0274-123456',
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'admin_wa', 
                'option_value' => '+6281234567890',
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'invoice_serie', 
                'option_value' => 'INV',
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'invoice_due_days', 
                'option_value' => 5,
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'invoice_auto_approval', 
                'option_value' => 1,
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'invoice_issue_days_before_expire', 
                'option_value' => 14,
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'issue_invoice_days_before_expire', 
                'option_value' => 7,
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'invoice_refund_logic', 
                'option_value' => 'credit_note',
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'invoice_cn_series', 
                'option_value' => 'CN-',
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'invoice_cn_starting_number', 
                'option_value' => 1,
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'allow_add_category', 
                'option_value' => 0,
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'option_name' => 'required_district_id', 
                'option_value' => 0,
                'autoload' => 'yes',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ];
        DB::table('options')->insert($options);
    }
}
